<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\Models\BeautyAiTask;
use App\Domains\Beauty\Models\BeautyAnalysisResult;
use App\Domains\Beauty\Models\BeautyProfile;
use App\Domains\Beauty\Models\BeautyProfileSnapshot;
use App\Domains\Beauty\Models\BeautyQuotaAccount;
use App\Domains\Beauty\Models\BeautyQuotaEvent;
use App\Domains\Beauty\Models\BeautySession;
use App\Domains\Beauty\Models\BeautyProductMapping;
use App\Domains\Beauty\Models\BeautyRecommendation;
use App\Domains\Storage\Models\MediaAsset;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;
use Marvel\Enums\Permission;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BeautySessionService
{
    public function __construct(
        protected FeatureExtractorService $features,
        protected RecommendationScoringService $scoring,
        protected ScoreVersionService $scoreVersion,
    ) {
    }

    public function createSession(array $payload, User $actor): BeautySession
    {
        $shopId = (int) $payload['shop_id'];
        $this->authorizeShop($actor, $shopId);
        $customerContext = $this->resolveCustomerContext($payload);
        $normalizedTraits = $this->features->normalizeInput($payload);

        return DB::transaction(function () use ($payload, $actor, $shopId, $customerContext, $normalizedTraits) {
            $beautyProfile = $this->upsertBeautyProfile($payload, $shopId, $customerContext, $normalizedTraits);

            $session = BeautySession::create([
                'public_id' => (string) Str::uuid(),
                'shop_id' => $shopId,
                'consultant_user_id' => $actor->id,
                'customer_id' => $customerContext['customer_id'],
                'user_profile_id' => $customerContext['user_profile_id'],
                'beauty_profile_id' => $beautyProfile->id,
                'consultation_mode' => $payload['consultation_mode'],
                'session_state' => BeautySession::STATE_DRAFT,
                'notes' => $payload['notes'] ?? null,
            ]);

            $snapshot = BeautyProfileSnapshot::create([
                'beauty_profile_id' => $beautyProfile->id,
                'beauty_session_id' => $session->id,
                'customer_id' => $customerContext['customer_id'],
                'user_profile_id' => $customerContext['user_profile_id'],
                'source' => $payload['consultation_mode'],
                'customer_name' => $beautyProfile->customer_name,
                'contact_email' => $beautyProfile->contact_email,
                'contact_phone' => $beautyProfile->contact_phone,
                'skin_type_tags' => $normalizedTraits['skin_type_tags'],
                'tone_tags' => $normalizedTraits['tone_tags'],
                'undertone_tags' => $normalizedTraits['undertone_tags'],
                'concern_tags' => $normalizedTraits['concern_tags'],
                'ingredient_tags' => $normalizedTraits['ingredient_tags'],
                'avoid_tags' => $normalizedTraits['avoid_tags'],
                'notes' => $payload['notes'] ?? null,
                'snapshot_payload' => [
                    'consultation_mode' => $payload['consultation_mode'],
                ],
            ]);

            $session->forceFill(['current_snapshot_id' => $snapshot->id])->save();

            $quotaAccount = $this->ensureQuotaAccount($shopId);
            $this->recordQuotaEvent($quotaAccount, $session, 'session_created', 0, [
                'consultation_mode' => $payload['consultation_mode'],
            ]);

            return $session->fresh($this->sessionRelations());
        });
    }

    public function showSession(string $publicId, User $actor): BeautySession
    {
        $session = $this->resolveSession($publicId);
        $this->authorizeShop($actor, (int) $session->shop_id);

        return $session;
    }

    public function attachMedia(string $publicId, int $mediaAssetId, User $actor): BeautySession
    {
        $session = $this->resolveSession($publicId);
        $this->authorizeShop($actor, (int) $session->shop_id);

        $mediaAsset = MediaAsset::findOrFail($mediaAssetId);

        if ($mediaAsset->status !== 'confirmed') {
            throw new \RuntimeException('Only confirmed media assets can be attached to a consultation.');
        }

        if ($mediaAsset->visibility !== 'private') {
            throw new \RuntimeException('Consultation media must remain private.');
        }

        if ((int) $mediaAsset->shop_id !== (int) $session->shop_id) {
            throw new AccessDeniedHttpException('The selected media asset does not belong to this shop consultation.');
        }

        DB::transaction(function () use ($session, $mediaAsset) {
            $session->forceFill([
                'primary_media_asset_id' => $mediaAsset->id,
                'session_state' => BeautySession::STATE_ANALYSIS_PENDING,
            ])->save();

            $task = BeautyAiTask::firstOrCreate(
                [
                    'beauty_session_id' => $session->id,
                    'provider' => 'perfectcorp_placeholder',
                    'task_type' => 'beauty_analysis',
                ],
                [
                    'status' => 'pending',
                    'request_payload' => [
                        'media_asset_id' => $mediaAsset->id,
                        'object_key' => $mediaAsset->object_key,
                    ],
                    'queued_at' => now(),
                ],
            );

            BeautyAnalysisResult::updateOrCreate(
                [
                    'beauty_session_id' => $session->id,
                    'provider' => 'perfectcorp_placeholder',
                ],
                [
                    'beauty_ai_task_id' => $task->id,
                    'status' => 'pending',
                    'summary_payload' => [
                        'placeholder' => true,
                        'message' => 'Provider analysis is deferred to a later phase.',
                    ],
                    'normalized_traits' => $this->snapshotTraits($session->currentSnapshot),
                    'recommendation_count' => 0,
                ],
            );

            $quotaAccount = $this->ensureQuotaAccount((int) $session->shop_id);
            $this->recordQuotaEvent($quotaAccount, $session, 'media_attached', 0, [
                'media_asset_id' => $mediaAsset->id,
            ]);
        });

        return $session->fresh($this->sessionRelations());
    }

    public function generateRecommendations(string $publicId, User $actor, int $limit = 10): array
    {
        $session = $this->resolveSession($publicId);
        $this->authorizeShop($actor, (int) $session->shop_id);

        $snapshot = $session->currentSnapshot;

        if (!$snapshot) {
            throw new \RuntimeException('This consultation session does not yet have a profile snapshot.');
        }

        $input = $this->features->normalizeInput($this->snapshotTraits($snapshot));

        $results = DB::transaction(function () use ($session, $input, $limit) {
            BeautyRecommendation::query()
                ->where('session_id', $session->public_id)
                ->delete();

            $results = BeautyProductMapping::query()
                ->with(['product.tags', 'product.shop'])
                ->whereHas('product', fn ($query) => $query->where('shop_id', $session->shop_id))
                ->get()
                ->map(function (BeautyProductMapping $mapping) use ($input) {
                    return $this->scoring->score(
                        $input,
                        $mapping,
                        $mapping->product,
                        $this->features->productTags($mapping->product),
                    );
                })
                ->sortByDesc(fn ($result) => $result->score)
                ->take($limit)
                ->values();

            foreach ($results as $result) {
                BeautyRecommendation::create([
                    'customer_id' => $session->customer_id,
                    'profile_id' => $session->user_profile_id,
                    'session_id' => $session->public_id,
                    'product_id' => $result->productId,
                    'score' => $result->score,
                    'confidence' => $result->confidence,
                    'reasons_json' => $result->reasons,
                    'warnings_json' => $result->warnings,
                    'breakdown_json' => $result->breakdown,
                    'score_version' => $this->scoreVersion->current(),
                    'accepted' => null,
                    'dismissed' => null,
                ]);
            }

            $task = BeautyAiTask::query()
                ->where('beauty_session_id', $session->id)
                ->where('provider', 'perfectcorp_placeholder')
                ->where('task_type', 'beauty_analysis')
                ->latest('id')
                ->first();

            if ($task) {
                $task->forceFill([
                    'status' => 'completed',
                    'response_payload' => [
                        'recommendation_count' => $results->count(),
                        'completed_with' => 'deterministic_phase4_foundation',
                    ],
                    'completed_at' => now(),
                ])->save();
            }

            BeautyAnalysisResult::updateOrCreate(
                [
                    'beauty_session_id' => $session->id,
                    'provider' => 'perfectcorp_placeholder',
                ],
                [
                    'beauty_ai_task_id' => $task?->id,
                    'status' => 'completed',
                    'summary_payload' => [
                        'placeholder' => true,
                        'message' => 'Recommendations derived from stored consultation criteria.',
                    ],
                    'normalized_traits' => $input,
                    'recommendation_count' => $results->count(),
                    'completed_at' => now(),
                ],
            );

            $session->forceFill([
                'session_state' => BeautySession::STATE_ANALYSIS_COMPLETED,
            ])->save();

            $quotaAccount = $this->ensureQuotaAccount((int) $session->shop_id);
            $this->recordQuotaEvent($quotaAccount, $session, 'recommendations_generated', 1, [
                'recommendation_count' => $results->count(),
            ]);

            return $results;
        });

        return $results->map->toArray()->all();
    }

    public function saveSession(string $publicId, User $actor, array $payload = []): BeautySession
    {
        $session = $this->resolveSession($publicId);
        $this->authorizeShop($actor, (int) $session->shop_id);

        DB::transaction(function () use ($session, $actor, $payload) {
            $session->forceFill([
                'session_state' => BeautySession::STATE_SAVED,
                'saved_at' => now(),
                'notes' => $payload['notes'] ?? $session->notes,
            ])->save();

            if (!empty($payload['accepted_recommendation_ids'])) {
                BeautyRecommendation::query()
                    ->where('session_id', $session->public_id)
                    ->update(['accepted' => false]);

                BeautyRecommendation::query()
                    ->where('session_id', $session->public_id)
                    ->whereIn('id', $payload['accepted_recommendation_ids'])
                    ->update(['accepted' => true]);
            }

            $this->recordAuditLog($actor, 'beauty_session.saved', $session, [
                'accepted_recommendation_ids' => $payload['accepted_recommendation_ids'] ?? [],
            ]);
        });

        return $session->fresh($this->sessionRelations());
    }

    public function discardSession(string $publicId, User $actor, array $payload = []): BeautySession
    {
        $session = $this->resolveSession($publicId);
        $this->authorizeShop($actor, (int) $session->shop_id);

        DB::transaction(function () use ($session, $actor, $payload) {
            $session->forceFill([
                'session_state' => BeautySession::STATE_DISCARDED,
                'discarded_at' => now(),
                'notes' => $payload['discard_reason'] ?? $session->notes,
            ])->save();

            $this->recordAuditLog($actor, 'beauty_session.discarded', $session, [
                'discard_reason' => $payload['discard_reason'] ?? null,
            ]);
        });

        return $session->fresh($this->sessionRelations());
    }

    protected function resolveSession(string $publicId): BeautySession
    {
        return BeautySession::query()
            ->with($this->sessionRelations())
            ->where('public_id', $publicId)
            ->firstOrFail();
    }

    protected function upsertBeautyProfile(
        array $payload,
        int $shopId,
        array $customerContext,
        array $normalizedTraits,
    ): BeautyProfile {
        $attributes = [
            'shop_id' => $shopId,
            'customer_id' => $customerContext['customer_id'],
            'user_profile_id' => $customerContext['user_profile_id'],
            'source' => $payload['consultation_mode'],
            'customer_name' => $payload['customer_name'] ?? $customerContext['customer_name'],
            'contact_email' => $payload['contact_email'] ?? $customerContext['contact_email'],
            'contact_phone' => $payload['contact_phone'] ?? $customerContext['contact_phone'],
            'skin_type_tags' => $normalizedTraits['skin_type_tags'],
            'tone_tags' => $normalizedTraits['tone_tags'],
            'undertone_tags' => $normalizedTraits['undertone_tags'],
            'concern_tags' => $normalizedTraits['concern_tags'],
            'ingredient_tags' => $normalizedTraits['ingredient_tags'],
            'avoid_tags' => $normalizedTraits['avoid_tags'],
            'notes' => $payload['notes'] ?? null,
        ];

        if ($payload['consultation_mode'] !== 'returning_customer') {
            return BeautyProfile::create($attributes);
        }

        return BeautyProfile::updateOrCreate(
            [
                'shop_id' => $shopId,
                'customer_id' => $customerContext['customer_id'],
                'user_profile_id' => $customerContext['user_profile_id'],
                'source' => $payload['consultation_mode'],
            ],
            $attributes,
        );
    }

    protected function resolveCustomerContext(array $payload): array
    {
        if ($payload['consultation_mode'] !== 'returning_customer') {
            return [
                'customer_id' => null,
                'user_profile_id' => null,
                'customer_name' => $payload['customer_name'] ?? null,
                'contact_email' => $payload['contact_email'] ?? null,
                'contact_phone' => $payload['contact_phone'] ?? null,
            ];
        }

        $customer = User::with('profile')->findOrFail((int) $payload['customer_id']);
        $userProfile = $payload['user_profile_id'] ?? optional($customer->profile)->id;

        if ($userProfile && $customer->profile && (int) $userProfile !== (int) $customer->profile->id) {
            throw new AccessDeniedHttpException('The selected profile does not belong to the selected customer.');
        }

        return [
            'customer_id' => (int) $customer->id,
            'user_profile_id' => $userProfile ? (int) $userProfile : null,
            'customer_name' => $customer->name,
            'contact_email' => $customer->email,
            'contact_phone' => optional($customer->profile)->contact,
        ];
    }

    protected function authorizeShop(User $actor, int $shopId): void
    {
        if ($actor->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return;
        }

        $shop = Shop::with('staffs')->find($shopId);

        if (!$shop) {
            throw new AccessDeniedHttpException('Consultation shop could not be resolved.');
        }

        if ($actor->hasPermissionTo(Permission::STORE_OWNER) && (int) $shop->owner_id === (int) $actor->id) {
            return;
        }

        if ($actor->hasPermissionTo(Permission::STAFF) && $shop->staffs->contains('id', $actor->id)) {
            return;
        }

        throw new AccessDeniedHttpException('You do not have permission to manage consultations for that shop.');
    }

    protected function ensureQuotaAccount(int $shopId): BeautyQuotaAccount
    {
        return BeautyQuotaAccount::firstOrCreate(
            [
                'shop_id' => $shopId,
                'provider' => 'perfectcorp_placeholder',
                'feature_key' => 'seller_consultation',
            ],
            [
                'plan_code' => 'phase4-foundation',
                'allocated_units' => null,
                'used_units' => 0,
            ],
        );
    }

    protected function recordQuotaEvent(
        BeautyQuotaAccount $quotaAccount,
        BeautySession $session,
        string $eventType,
        int $deltaUnits,
        array $metadata = [],
    ): void {
        if ($deltaUnits !== 0) {
            $quotaAccount->increment('used_units', $deltaUnits);
        }

        BeautyQuotaEvent::create([
            'beauty_quota_account_id' => $quotaAccount->id,
            'beauty_session_id' => $session->id,
            'event_type' => $eventType,
            'delta_units' => $deltaUnits,
            'metadata_json' => $metadata,
            'occurred_at' => now(),
        ]);
    }

    protected function recordAuditLog(User $actor, string $action, BeautySession $session, array $metadata = []): void
    {
        AuditLog::create([
            'user_id' => $actor->id,
            'auditable_type' => BeautySession::class,
            'auditable_id' => $session->id,
            'action' => $action,
            'metadata_json' => array_merge($metadata, [
                'public_id' => $session->public_id,
                'shop_id' => $session->shop_id,
            ]),
        ]);
    }

    protected function snapshotTraits(?BeautyProfileSnapshot $snapshot): array
    {
        if (!$snapshot) {
            return [
                'skin_type_tags' => [],
                'tone_tags' => [],
                'undertone_tags' => [],
                'concern_tags' => [],
                'ingredient_tags' => [],
                'avoid_tags' => [],
            ];
        }

        return [
            'skin_type_tags' => $snapshot->skin_type_tags ?? [],
            'tone_tags' => $snapshot->tone_tags ?? [],
            'undertone_tags' => $snapshot->undertone_tags ?? [],
            'concern_tags' => $snapshot->concern_tags ?? [],
            'ingredient_tags' => $snapshot->ingredient_tags ?? [],
            'avoid_tags' => $snapshot->avoid_tags ?? [],
        ];
    }

    protected function sessionRelations(): array
    {
        return [
            'shop:id,name,slug',
            'consultant:id,name,email',
            'customer:id,name,email',
            'userProfile:id,customer_id,contact',
            'beautyProfile',
            'currentSnapshot',
            'primaryMediaAsset',
            'aiTasks',
            'analysisResults',
            'quotaEvents',
        ];
    }
}
