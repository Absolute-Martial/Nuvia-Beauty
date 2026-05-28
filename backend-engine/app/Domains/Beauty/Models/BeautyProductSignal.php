<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Marvel\Database\Models\Product;
use Marvel\Database\Models\Shop;

class BeautyProductSignal extends Model
{
    protected $table = 'beauty_product_signals';

    protected $guarded = [];

    protected $casts = [
        'weighted_score' => 'float',
        'last_event_at' => 'datetime',
        'last_recomputed_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
