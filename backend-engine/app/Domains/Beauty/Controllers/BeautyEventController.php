<?php

namespace App\Domains\Beauty\Controllers;

use App\Domains\Beauty\Models\BeautyEvent;
use App\Domains\Beauty\Services\BeautyEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Marvel\Database\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BeautyEventController extends Controller
{
    public function __construct(
        protected BeautyEventService $events,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'event_type' => ['required', 'in:view,add_to_cart,purchase'],
            'customer_id' => ['nullable', 'integer', 'exists:users,id'],
            'profile_id' => ['nullable', 'integer', 'exists:user_profiles,id'],
            'session_id' => ['nullable', 'string', 'max:100'],
            'recommendation_id' => ['nullable', 'integer', 'exists:beauty_recommendations,id'],
            'source_surface' => ['nullable', 'in:storefront,admin_panel,vendor_portal'],
            'metadata' => ['nullable', 'array'],
        ]);

        $ownership = $this->resolveOwnershipContext($validated, $user);
        $event = $this->events->record(array_merge($validated, $ownership));

        return response()->json([
            'data' => [
                'event' => [
                    'id' => $event->id,
                    'product_id' => (int) $event->product_id,
                    'event_type' => $event->event_type,
                    'source_surface' => $event->source_surface,
                    'occurred_at' => optional($event->occurred_at)->toIso8601String(),
                ],
                'signal' => $event->signalSummary(),
            ],
        ], 201);
    }

    protected function resolveOwnershipContext(array $validated, ?User $user): array
    {
        $requestedCustomerId = isset($validated['customer_id']) ? (int) $validated['customer_id'] : null;
        $requestedProfileId = isset($validated['profile_id']) ? (int) $validated['profile_id'] : null;

        if (!$user) {
            if ($requestedCustomerId || $requestedProfileId) {
                throw new AccessDeniedHttpException('Anonymous beauty events cannot target a stored customer or profile.');
            }

            return [
                'customer_id' => null,
                'profile_id' => null,
            ];
        }

        if ($user->hasPermissionTo(\Marvel\Enums\Permission::SUPER_ADMIN)) {
            return [
                'customer_id' => $requestedCustomerId ?? (int) $user->id,
                'profile_id' => $requestedProfileId ?? (optional($user->profile)->id ? (int) optional($user->profile)->id : null),
            ];
        }

        if ($requestedCustomerId && $requestedCustomerId !== (int) $user->id) {
            throw new AccessDeniedHttpException('You can only create beauty events for your own customer account.');
        }

        if ($requestedProfileId && $requestedProfileId !== (int) optional($user->profile)->id) {
            throw new AccessDeniedHttpException('You can only create beauty events for your own profile.');
        }

        return [
            'customer_id' => (int) $user->id,
            'profile_id' => optional($user->profile)->id ? (int) optional($user->profile)->id : null,
        ];
    }
}
