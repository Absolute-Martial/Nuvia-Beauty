<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Marvel\Database\Models\Profile;
use Marvel\Database\Models\User;

class BeautyProfileSnapshot extends Model
{
    protected $table = 'beauty_profile_snapshots';

    protected $guarded = [];

    protected $casts = [
        'skin_type_tags' => 'array',
        'tone_tags' => 'array',
        'undertone_tags' => 'array',
        'concern_tags' => 'array',
        'ingredient_tags' => 'array',
        'avoid_tags' => 'array',
        'snapshot_payload' => 'array',
    ];

    public function beautyProfile(): BelongsTo
    {
        return $this->belongsTo(BeautyProfile::class, 'beauty_profile_id');
    }

    public function beautySession(): BelongsTo
    {
        return $this->belongsTo(BeautySession::class, 'beauty_session_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }
}
