<?php

namespace App\Domains\Beauty\Controllers;

use App\Domains\Beauty\Models\BeautyProductMapping;
use App\Domains\Beauty\Models\BeautyRecommendation;
use App\Domains\Beauty\Services\FeatureExtractorService;
use App\Domains\Beauty\Services\RecommendationScoringService;
use App\Domains\Beauty\Services\ScoreVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Marvel\Enums\Permission;
use Marvel\Database\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BeautyRecommendationController extends Controller
{
    public function __construct(
        protected FeatureExtractorService $features,
        protected RecommendationScoringService $scoring,
        protected ScoreVersionService $scoreVersion,
    ) {
    }

    public function generate(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();
        $validated = $request->validate([
            'customer_id' => ['nullable', 'integer', 'exists:users,id'],
            'profile_id' => ['nullable', 'integer', 'exists:user_profiles,id'],
            'session_id' => ['nullable', 'string', 'max:100'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'skin_type_tags' => ['nullable', 'array'],
            'skin_type_tags.*' => ['string', 'max:50'],
            'tone_tags' => ['nullable', 'array'],
            'tone_tags.*' => ['string', 'max:50'],
            'undertone_tags' => ['nullable', 'array'],
            'undertone_tags.*' => ['string', 'max:50'],
            'concern_tags' => ['nullable', 'array'],
            'concern_tags.*' => ['string', 'max:50'],
            'ingredient_tags' => ['nullable', 'array'],
            'ingredient_tags.*' => ['string', 'max:50'],
            'avoid_tags' => ['nullable', 'array'],
            'avoid_tags.*' => ['string', 'max:50'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $limit = (int) ($validated['limit'] ?? 10);
        $normalizedInput = $this->features->normalizeInput($validated);
        $ownership = $this->resolveOwnershipContext($validated, $user);

        $query = BeautyProductMapping::query()
            ->with(['product.tags', 'product.shop'])
            ->whereHas('product');

        if (!empty($validated['product_ids'])) {
            $query->whereIn('product_id', $validated['product_ids']);
        }

        $results = $query->get()
            ->map(function (BeautyProductMapping $mapping) use ($normalizedInput) {
                return $this->scoring->score(
                    $normalizedInput,
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
                'customer_id' => $ownership['customer_id'],
                'profile_id' => $ownership['profile_id'],
                'session_id' => $ownership['session_id'],
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

        return response()->json([
            'data' => [
                'recommendations' => $results->map->toArray()->all(),
            ],
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $recommendation = BeautyRecommendation::with('product')->findOrFail($id);
        $user = $request->user();

        if (!$this->canViewRecommendation($recommendation, $user, (string) $request->query('session_id', ''))) {
            throw new AccessDeniedHttpException('You do not have permission to view that recommendation.');
        }

        return response()->json([
            'data' => [
                'recommendation' => [
                    'id' => $recommendation->id,
                    'product_id' => $recommendation->product_id,
                    'score' => (int) $recommendation->score,
                    'confidence' => $recommendation->confidence,
                    'reasons' => $recommendation->reasons_json ?? [],
                    'warnings' => $recommendation->warnings_json ?? [],
                    'breakdown' => $recommendation->breakdown_json ?? [],
                    'score_version' => $recommendation->score_version,
                    'accepted' => $recommendation->accepted,
                    'dismissed' => $recommendation->dismissed,
                ],
            ],
        ]);
    }

    protected function canViewRecommendation(BeautyRecommendation $recommendation, ?User $user, string $sessionId): bool
    {
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return true;
        }

        if ($user && (int) $recommendation->customer_id === (int) $user->id) {
            return true;
        }

        if ($user && (int) $recommendation->profile_id === (int) optional($user->profile)->id) {
            return true;
        }

        return !empty($recommendation->session_id) && $recommendation->session_id === $sessionId;
    }

    protected function resolveOwnershipContext(array $validated, ?User $user): array
    {
        $requestedCustomerId = isset($validated['customer_id']) ? (int) $validated['customer_id'] : null;
        $requestedProfileId = isset($validated['profile_id']) ? (int) $validated['profile_id'] : null;
        $sessionId = $validated['session_id'] ?? null;

        if (!$user) {
            if ($requestedCustomerId || $requestedProfileId) {
                throw new AccessDeniedHttpException('Anonymous recommendation requests cannot target a stored customer or profile.');
            }

            return [
                'customer_id' => null,
                'profile_id' => null,
                'session_id' => $sessionId,
            ];
        }

        if ($user->hasPermissionTo(Permission::SUPER_ADMIN)) {
            $defaultProfileId = optional($user->profile)->id;

            return [
                'customer_id' => $requestedCustomerId ?? (int) $user->id,
                'profile_id' => $requestedProfileId ?? ($defaultProfileId ? (int) $defaultProfileId : null),
                'session_id' => $sessionId,
            ];
        }

        if ($requestedCustomerId && $requestedCustomerId !== (int) $user->id) {
            throw new AccessDeniedHttpException('You can only generate recommendations for your own customer account.');
        }

        if ($requestedProfileId && $requestedProfileId !== (int) optional($user->profile)->id) {
            throw new AccessDeniedHttpException('You can only generate recommendations for your own profile.');
        }

        $defaultProfileId = optional($user->profile)->id;

        return [
            'customer_id' => (int) $user->id,
            'profile_id' => $defaultProfileId ? (int) $defaultProfileId : null,
            'session_id' => $sessionId,
        ];
    }
}
