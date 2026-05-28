<?php

namespace App\Domains\Beauty\Controllers;

use App\Domains\Beauty\Services\BeautySessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Marvel\Database\Models\User;

class BeautySessionController extends Controller
{
    public function __construct(
        protected BeautySessionService $sessions,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validate([
            'shop_id' => ['required', 'integer', 'exists:shops,id'],
            'consultation_mode' => ['required', Rule::in(['guest', 'new_customer', 'returning_customer'])],
            'customer_id' => ['nullable', 'integer', 'exists:users,id'],
            'user_profile_id' => ['nullable', 'integer', 'exists:user_profiles,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
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
        ]);

        if ($validated['consultation_mode'] === 'returning_customer' && empty($validated['customer_id'])) {
            return response()->json([
                'message' => 'Returning customer consultations require a customer_id.',
            ], 422);
        }

        return response()->json([
            'data' => [
                'session' => $this->serializeSession(
                    $this->sessions->createSession($validated, $actor)
                ),
            ],
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        return response()->json([
            'data' => [
                'session' => $this->serializeSession(
                    $this->sessions->showSession($id, $actor)
                ),
            ],
        ]);
    }

    public function attachMedia(Request $request, string $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validate([
            'media_asset_id' => ['required', 'integer', 'exists:beauty_media_assets,id'],
        ]);

        return response()->json([
            'data' => [
                'session' => $this->serializeSession(
                    $this->sessions->attachMedia($id, (int) $validated['media_asset_id'], $actor)
                ),
            ],
        ]);
    }

    public function startAnalysis(Request $request, string $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $payload = $this->sessions->startAnalysis($id, $actor);

        return response()->json([
            'data' => [
                'session' => $this->serializeSession($payload['session']),
                'task' => $this->serializeTask($payload['task']),
                'analysis_result' => $this->serializeAnalysisResult($payload['analysis_result']),
                'recommendations' => $payload['recommendations'],
            ],
        ], 202);
    }

    public function save(Request $request, string $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
            'accepted_recommendation_ids' => ['nullable', 'array'],
            'accepted_recommendation_ids.*' => ['integer', 'exists:beauty_recommendations,id'],
        ]);

        return response()->json([
            'data' => [
                'session' => $this->serializeSession(
                    $this->sessions->saveSession($id, $actor, $validated)
                ),
            ],
        ]);
    }

    public function discard(Request $request, string $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validate([
            'discard_reason' => ['nullable', 'string'],
        ]);

        return response()->json([
            'data' => [
                'session' => $this->serializeSession(
                    $this->sessions->discardSession($id, $actor, $validated)
                ),
            ],
        ]);
    }

    public function recommendations(Request $request, string $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        return response()->json([
            'data' => [
                'recommendations' => $this->sessions->generateRecommendations(
                    $id,
                    $actor,
                    (int) ($validated['limit'] ?? 10),
                ),
            ],
        ]);
    }

    public function analysisStatus(Request $request, int $taskId): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $payload = $this->sessions->analysisStatus($taskId, $actor);

        return response()->json([
            'data' => [
                'session' => $this->serializeSession($payload['session']),
                'task' => $this->serializeTask($payload['task']),
                'analysis_result' => $this->serializeAnalysisResult($payload['analysis_result']),
                'recommendations' => $payload['recommendations'],
            ],
        ]);
    }

    protected function serializeSession($session): array
    {
        return [
            'id' => $session->public_id,
            'internal_id' => (int) $session->id,
            'shop' => $session->shop ? [
                'id' => (int) $session->shop->id,
                'name' => $session->shop->name,
                'slug' => $session->shop->slug,
            ] : null,
            'consultant' => $session->consultant ? [
                'id' => (int) $session->consultant->id,
                'name' => $session->consultant->name,
                'email' => $session->consultant->email,
            ] : null,
            'consultation_mode' => $session->consultation_mode,
            'session_state' => $session->session_state,
            'notes' => $session->notes,
            'saved_at' => optional($session->saved_at)?->toIso8601String(),
            'discarded_at' => optional($session->discarded_at)?->toIso8601String(),
            'customer' => $session->customer ? [
                'id' => (int) $session->customer->id,
                'name' => $session->customer->name,
                'email' => $session->customer->email,
            ] : null,
            'profile' => $session->beautyProfile ? [
                'id' => (int) $session->beautyProfile->id,
                'customer_name' => $session->beautyProfile->customer_name,
                'contact_email' => $session->beautyProfile->contact_email,
                'contact_phone' => $session->beautyProfile->contact_phone,
                'skin_type_tags' => $session->beautyProfile->skin_type_tags ?? [],
                'tone_tags' => $session->beautyProfile->tone_tags ?? [],
                'undertone_tags' => $session->beautyProfile->undertone_tags ?? [],
                'concern_tags' => $session->beautyProfile->concern_tags ?? [],
                'ingredient_tags' => $session->beautyProfile->ingredient_tags ?? [],
                'avoid_tags' => $session->beautyProfile->avoid_tags ?? [],
            ] : null,
            'snapshot' => $session->currentSnapshot ? [
                'id' => (int) $session->currentSnapshot->id,
                'skin_type_tags' => $session->currentSnapshot->skin_type_tags ?? [],
                'tone_tags' => $session->currentSnapshot->tone_tags ?? [],
                'undertone_tags' => $session->currentSnapshot->undertone_tags ?? [],
                'concern_tags' => $session->currentSnapshot->concern_tags ?? [],
                'ingredient_tags' => $session->currentSnapshot->ingredient_tags ?? [],
                'avoid_tags' => $session->currentSnapshot->avoid_tags ?? [],
                'notes' => $session->currentSnapshot->notes,
            ] : null,
            'media_asset' => $session->primaryMediaAsset ? [
                'id' => (int) $session->primaryMediaAsset->id,
                'disk_name' => $session->primaryMediaAsset->disk_name,
                'bucket' => $session->primaryMediaAsset->bucket,
                'object_key' => $session->primaryMediaAsset->object_key,
                'status' => $session->primaryMediaAsset->status,
                'content_type' => $session->primaryMediaAsset->content_type,
            ] : null,
            'ai_tasks' => $session->aiTasks->map(fn ($task) => [
                'id' => (int) $task->id,
                'provider' => $task->provider,
                'task_type' => $task->task_type,
                'status' => $task->status,
                'queued_at' => optional($task->queued_at)?->toIso8601String(),
                'completed_at' => optional($task->completed_at)?->toIso8601String(),
            ])->values()->all(),
            'analysis_results' => $session->analysisResults->map(fn ($result) => [
                'id' => (int) $result->id,
                'provider' => $result->provider,
                'status' => $result->status,
                'recommendation_count' => (int) $result->recommendation_count,
                'completed_at' => optional($result->completed_at)?->toIso8601String(),
            ])->values()->all(),
        ];
    }

    protected function serializeTask($task): ?array
    {
        if (!$task) {
            return null;
        }

        return [
            'id' => (int) $task->id,
            'provider' => $task->provider,
            'task_type' => $task->task_type,
            'status' => $task->status,
            'provider_task_id' => $task->provider_task_id,
            'error_message' => $task->error_message,
            'queued_at' => optional($task->queued_at)?->toIso8601String(),
            'started_at' => optional($task->started_at)?->toIso8601String(),
            'completed_at' => optional($task->completed_at)?->toIso8601String(),
        ];
    }

    protected function serializeAnalysisResult($result): ?array
    {
        if (!$result) {
            return null;
        }

        return [
            'id' => (int) $result->id,
            'provider' => $result->provider,
            'status' => $result->status,
            'summary' => $result->summary_payload ?? [],
            'normalized_traits' => $result->normalized_traits ?? [],
            'recommendation_count' => (int) $result->recommendation_count,
            'completed_at' => optional($result->completed_at)?->toIso8601String(),
        ];
    }
}
