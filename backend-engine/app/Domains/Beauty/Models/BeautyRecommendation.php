<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Marvel\Database\Models\Product;
use Marvel\Database\Models\Profile;
use Marvel\Database\Models\User;

class BeautyRecommendation extends Model
{
    protected $table = 'beauty_recommendations';

    protected $guarded = [];

    protected $casts = [
        'reasons_json' => 'array',
        'warnings_json' => 'array',
        'breakdown_json' => 'array',
        'accepted' => 'boolean',
        'dismissed' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
