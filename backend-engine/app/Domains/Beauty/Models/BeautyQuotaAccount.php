<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Marvel\Database\Models\Shop;

class BeautyQuotaAccount extends Model
{
    protected $table = 'beauty_quota_accounts';

    protected $guarded = [];

    protected $casts = [
        'reset_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(BeautyQuotaEvent::class, 'beauty_quota_account_id');
    }
}
