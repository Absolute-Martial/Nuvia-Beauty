<?php

namespace App\Domains\Beauty\DTO;

use Illuminate\Support\Str;

class KaggleCatalogItem
{
    public function __construct(
        public readonly int $rowIndex,
        public readonly array $raw,
        public readonly ?string $brand,
        public readonly ?string $name,
        public readonly ?string $category,
        public readonly ?string $description,
        public readonly ?string $ingredients,
        public readonly ?string $price,
        public readonly ?string $imageUrl,
        public readonly ?string $shade,
        public readonly ?string $color,
    ) {
    }

    public function searchableText(): string
    {
        return trim(strtolower(implode(' ', array_filter([
            $this->brand,
            $this->name,
            $this->category,
            $this->description,
            $this->ingredients,
            $this->shade,
            $this->color,
        ]))));
    }

    public function family(): string
    {
        $text = $this->searchableText();

        foreach (self::familyKeywords() as $family => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $family;
                }
            }
        }

        return 'general';
    }

    public function title(): string
    {
        return $this->name ?: $this->category ?: $this->brand ?: 'Unnamed product';
    }

    public function summary(): string
    {
        return trim(implode(' · ', array_filter([
            $this->brand,
            $this->category,
            $this->price ? '$' . $this->price : null,
        ])));
    }

    public function slugHint(): string
    {
        return Str::slug($this->title());
    }

    public function toArray(): array
    {
        return [
            'row_index' => $this->rowIndex,
            'brand' => $this->brand,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'price' => $this->price,
            'image_url' => $this->imageUrl,
            'shade' => $this->shade,
            'color' => $this->color,
            'family' => $this->family(),
            'summary' => $this->summary(),
        ];
    }

    protected static function familyKeywords(): array
    {
        return [
            'skincare' => [
                'serum', 'moisturizer', 'cream', 'cleanser', 'toner', 'mask',
                'sunscreen', 'spf', 'essence', 'ampoule', 'face wash', 'facial',
            ],
            'makeup' => [
                'foundation', 'concealer', 'powder', 'blush', 'bronzer', 'highlighter',
                'bb cream', 'cc cream', 'primer', 'palette', 'compact',
            ],
            'lip' => [
                'lipstick', 'lip gloss', 'lip balm', 'lip liner', 'tint',
            ],
            'eye' => [
                'mascara', 'eyeliner', 'eyeshadow', 'brow', 'eyebrow',
            ],
            'haircare' => [
                'shampoo', 'conditioner', 'hair', 'scalp', 'leave-in', 'treatment',
            ],
            'bodycare' => [
                'body', 'lotion', 'wash', 'scrub', 'deodorant', 'cream', 'butter',
            ],
            'fragrance' => [
                'perfume', 'eau de', 'mist', 'fragrance', 'scent',
            ],
            'nail' => [
                'nail', 'polish', 'cuticle',
            ],
            'tools' => [
                'brush', 'sponge', 'tool', 'roller', 'applier', 'curler',
            ],
        ];
    }
}
