<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Marvel\Database\Models\Product;

class BeautyProductMapping extends Model
{
    public const STATUS_MISSING = 'missing_mapping';
    public const STATUS_PARTIAL = 'partial_mapping';
    public const STATUS_READY = 'ready_for_recommendation';

    protected $table = 'beauty_product_mappings';

    protected $guarded = [];

    protected $casts = [
        'concern_tags' => 'array',
        'skin_type_tags' => 'array',
        'tone_tags' => 'array',
        'undertone_tags' => 'array',
        'ingredient_tags' => 'array',
        'avoid_tags' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function recommendationDimensionCount(): int
    {
        return collect([
            $this->skin_type_tags,
            $this->tone_tags,
            $this->undertone_tags,
            $this->concern_tags,
            $this->ingredient_tags,
        ])->filter(fn ($tags) => is_array($tags) && count($tags) > 0)->count();
    }

    public function hasSupplementalMappingData(): bool
    {
        return (is_array($this->avoid_tags) && count($this->avoid_tags) > 0)
            || !empty($this->explanation_template);
    }

    public function recommendationStatus(): string
    {
        return $this->recommendationDimensionCount() >= 2
            ? self::STATUS_READY
            : self::STATUS_PARTIAL;
    }

    public static function statusFor(?self $mapping): string
    {
        if (!$mapping) {
            return self::STATUS_MISSING;
        }

        return $mapping->recommendationStatus();
    }
}
