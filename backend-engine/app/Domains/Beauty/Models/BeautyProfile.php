<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Marvel\Database\Models\Profile;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;

class BeautyProfile extends Model
{
    protected $table = 'beauty_profiles';

    protected $guarded = [];

    protected $casts = [
        'skin_type_tags' => 'array',
        'tone_tags' => 'array',
        'undertone_tags' => 'array',
        'concern_tags' => 'array',
        'ingredient_tags' => 'array',
        'avoid_tags' => 'array',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(BeautyProfileSnapshot::class, 'beauty_profile_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(BeautySession::class, 'beauty_profile_id');
    }
}
