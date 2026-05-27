<?php

namespace App\Domains\Beauty\DTO;

class RecommendationResult
{
    public function __construct(
        public readonly int $productId,
        public readonly int $score,
        public readonly string $confidence,
        public readonly array $reasons,
        public readonly array $warnings,
        public readonly array $breakdown,
        public readonly array $product = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'score' => $this->score,
            'confidence' => $this->confidence,
            'reasons' => $this->reasons,
            'warnings' => $this->warnings,
            'breakdown' => $this->breakdown,
            'product' => $this->product,
        ];
    }
}
