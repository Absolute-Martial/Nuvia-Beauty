<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Marvel\Database\Models\Product;
use Marvel\Database\Models\Profile;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;

class BeautyEvent extends Model
{
    protected $table = 'beauty_events';

    protected $guarded = [];

    protected $casts = [
        'metadata_json' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function signalSummary(): ?array
    {
        $signal = BeautyProductSignal::query()->where('product_id', $this->product_id)->first();

        if (!$signal) {
            return null;
        }

        return [
            'product_id' => (int) $signal->product_id,
            'view_count' => (int) $signal->view_count,
            'add_to_cart_count' => (int) $signal->add_to_cart_count,
            'purchase_count' => (int) $signal->purchase_count,
            'weighted_score' => (float) $signal->weighted_score,
        ];
    }
}
