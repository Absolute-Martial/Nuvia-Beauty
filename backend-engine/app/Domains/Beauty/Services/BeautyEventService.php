<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\Models\BeautyEvent;
use Illuminate\Support\Arr;
use Marvel\Database\Models\Product;

class BeautyEventService
{
    public function __construct(
        protected BeautyProductSignalService $signals,
    ) {
    }

    public function record(array $payload): BeautyEvent
    {
        $product = Product::query()->findOrFail((int) $payload['product_id']);
        $event = BeautyEvent::create([
            'customer_id' => $payload['customer_id'] ?? null,
            'profile_id' => $payload['profile_id'] ?? null,
            'session_id' => $payload['session_id'] ?? null,
            'product_id' => (int) $product->id,
            'shop_id' => $product->shop_id,
            'event_type' => $payload['event_type'],
            'source_surface' => $payload['source_surface'] ?? null,
            'recommendation_id' => $payload['recommendation_id'] ?? null,
            'metadata_json' => $this->sanitizeMetadata($payload['metadata'] ?? []),
            'occurred_at' => now(),
        ]);

        $this->signals->recordIncrement($event);

        return $event;
    }

    protected function sanitizeMetadata(array $metadata): array
    {
        $whitelist = [
            'placement',
            'context',
            'ui_variant',
            'query_id',
        ];

        return collect(Arr::only($metadata, $whitelist))
            ->filter(fn ($value) => is_scalar($value) || $value === null)
            ->map(fn ($value) => is_string($value) ? mb_substr($value, 0, 120) : $value)
            ->all();
    }
}
