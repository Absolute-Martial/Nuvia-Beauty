<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\Models\BeautyEvent;
use App\Domains\Beauty\Models\BeautyProductSignal;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class BeautyProductSignalService
{
    public const SIGNAL_VERSION = 'phase4_v1';

    public function recordIncrement(BeautyEvent $event): BeautyProductSignal
    {
        $signal = BeautyProductSignal::firstOrNew(['product_id' => (int) $event->product_id]);
        $signal->shop_id = $event->shop_id;
        $signal->view_count = (int) $signal->view_count + ($event->event_type === 'view' ? 1 : 0);
        $signal->add_to_cart_count = (int) $signal->add_to_cart_count + ($event->event_type === 'add_to_cart' ? 1 : 0);
        $signal->purchase_count = (int) $signal->purchase_count + ($event->event_type === 'purchase' ? 1 : 0);
        $signal->weighted_score = $this->weightedScore(
            (int) $signal->view_count,
            (int) $signal->add_to_cart_count,
            (int) $signal->purchase_count,
        );
        $signal->signal_version = self::SIGNAL_VERSION;
        $signal->last_event_at = $this->latestTimestamp($signal->last_event_at, $event->occurred_at);
        $signal->last_recomputed_at = now();
        $signal->save();

        return $signal;
    }

    public function recompute(array $productIds = []): array
    {
        $targetProductIds = collect($productIds)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($targetProductIds->isEmpty()) {
            $targetProductIds = BeautyEvent::query()
                ->distinct()
                ->orderBy('product_id')
                ->pluck('product_id')
                ->map(fn ($id) => (int) $id);
        }

        $recomputed = 0;

        foreach ($targetProductIds as $productId) {
            $events = BeautyEvent::query()
                ->where('product_id', $productId)
                ->orderBy('occurred_at')
                ->get();

            if ($events->isEmpty()) {
                continue;
            }

            $viewCount = $events->where('event_type', 'view')->count();
            $addToCartCount = $events->where('event_type', 'add_to_cart')->count();
            $purchaseCount = $events->where('event_type', 'purchase')->count();
            $latestEvent = $events->last();

            BeautyProductSignal::updateOrCreate(
                ['product_id' => $productId],
                [
                    'shop_id' => $latestEvent->shop_id,
                    'view_count' => $viewCount,
                    'add_to_cart_count' => $addToCartCount,
                    'purchase_count' => $purchaseCount,
                    'weighted_score' => $this->weightedScore($viewCount, $addToCartCount, $purchaseCount),
                    'signal_version' => self::SIGNAL_VERSION,
                    'last_event_at' => $latestEvent->occurred_at,
                    'last_recomputed_at' => now(),
                ],
            );

            $recomputed++;
        }

        return [
            'product_ids' => $targetProductIds->all(),
            'recomputed_count' => $recomputed,
            'signal_version' => self::SIGNAL_VERSION,
        ];
    }

    protected function weightedScore(int $views, int $addToCart, int $purchases): float
    {
        return round(($views * 1.0) + ($addToCart * 3.0) + ($purchases * 8.0), 2);
    }

    protected function latestTimestamp($existing, ?CarbonInterface $incoming): ?CarbonInterface
    {
        if (!$existing) {
            return $incoming;
        }

        if (!$incoming) {
            return $existing;
        }

        return $existing->greaterThan($incoming) ? $existing : $incoming;
    }
}
