<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\DTO\RecommendationResult;
use App\Domains\Beauty\Models\BeautyProductMapping;
use Marvel\Database\Models\Product;

class RecommendationScoringService
{
    public function __construct(
        protected RecommendationExplanationService $explanations,
    ) {
    }

    public function score(
        array $input,
        BeautyProductMapping $mapping,
        Product $product,
        array $productTags,
    ): RecommendationResult {
        $mappingData = [
            'skin_type_tags' => $mapping->skin_type_tags ?? [],
            'tone_tags' => $mapping->tone_tags ?? [],
            'undertone_tags' => $mapping->undertone_tags ?? [],
            'concern_tags' => $mapping->concern_tags ?? [],
            'ingredient_tags' => $mapping->ingredient_tags ?? [],
            'avoid_tags' => $mapping->avoid_tags ?? [],
        ];

        $breakdown = [
            'skin_type_match' => $this->ratio($input['skin_type_tags'], $mappingData['skin_type_tags']),
            'tone_match' => $this->ratio($input['tone_tags'], $mappingData['tone_tags']),
            'undertone_match' => $this->ratio($input['undertone_tags'], $mappingData['undertone_tags']),
            'concern_match' => $this->ratio($input['concern_tags'], $mappingData['concern_tags']),
            'ingredient_match' => $this->ratio($input['ingredient_tags'], $mappingData['ingredient_tags']),
            'product_tag_signal' => $this->ratio(
                array_unique(array_merge($input['concern_tags'], $input['ingredient_tags'])),
                $productTags
            ),
            'avoid_penalty' => count(array_intersect($input['avoid_tags'], $mappingData['avoid_tags'])) > 0 ? 1.0 : 0.0,
        ];

        $weightedScore =
            ($breakdown['skin_type_match'] * 0.22) +
            ($breakdown['tone_match'] * 0.14) +
            ($breakdown['undertone_match'] * 0.14) +
            ($breakdown['concern_match'] * 0.24) +
            ($breakdown['ingredient_match'] * 0.12) +
            ($breakdown['product_tag_signal'] * 0.14);

        $score = (int) round(max(0, min(100, ($weightedScore * 100) - ($breakdown['avoid_penalty'] * 55))));

        $confidence = match (true) {
            $breakdown['avoid_penalty'] > 0 => 'low',
            $score >= 80 => 'high',
            $score >= 55 => 'medium',
            default => 'low',
        };

        return new RecommendationResult(
            productId: (int) $product->id,
            score: $score,
            confidence: $confidence,
            reasons: $this->explanations->reasons($input, $mappingData, $productTags, $breakdown),
            warnings: $this->explanations->warnings($input, $mappingData),
            breakdown: $breakdown,
            product: [
                'id' => (int) $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'shop_id' => $product->shop_id,
            ],
        );
    }

    protected function ratio(array $inputTags, array $mappingTags): float
    {
        if (empty($inputTags) || empty($mappingTags)) {
            return 0.0;
        }

        return round(count(array_intersect($inputTags, $mappingTags)) / count($mappingTags), 2);
    }
}
