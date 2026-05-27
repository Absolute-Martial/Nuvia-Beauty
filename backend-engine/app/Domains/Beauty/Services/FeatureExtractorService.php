<?php

namespace App\Domains\Beauty\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Marvel\Database\Models\Product;

class FeatureExtractorService
{
    public function normalizeInput(array $payload): array
    {
        return [
            'skin_type_tags' => $this->normalizeTags($payload['skin_type_tags'] ?? []),
            'tone_tags' => $this->normalizeTags($payload['tone_tags'] ?? []),
            'undertone_tags' => $this->normalizeTags($payload['undertone_tags'] ?? []),
            'concern_tags' => $this->normalizeTags($payload['concern_tags'] ?? []),
            'ingredient_tags' => $this->normalizeTags($payload['ingredient_tags'] ?? []),
            'avoid_tags' => $this->normalizeTags($payload['avoid_tags'] ?? []),
        ];
    }

    public function normalizeMapping(array $mapping): array
    {
        return [
            'skin_type_tags' => $this->normalizeTags($mapping['skin_type_tags'] ?? []),
            'tone_tags' => $this->normalizeTags($mapping['tone_tags'] ?? []),
            'undertone_tags' => $this->normalizeTags($mapping['undertone_tags'] ?? []),
            'concern_tags' => $this->normalizeTags($mapping['concern_tags'] ?? []),
            'ingredient_tags' => $this->normalizeTags($mapping['ingredient_tags'] ?? []),
            'avoid_tags' => $this->normalizeTags($mapping['avoid_tags'] ?? []),
        ];
    }

    public function productTags(Product $product): array
    {
        return $this->normalizeTags(
            $product->relationLoaded('tags')
                ? $product->tags->pluck('slug')->all()
                : []
        );
    }

    protected function normalizeTags(array|string|null $value): array
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        return Collection::make(Arr::wrap($value))
            ->map(fn ($tag) => strtolower(trim((string) $tag)))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
