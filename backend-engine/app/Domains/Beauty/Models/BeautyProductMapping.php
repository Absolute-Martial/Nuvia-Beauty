<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Marvel\Database\Models\Product;

class BeautyProductMapping extends Model
{
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
}
