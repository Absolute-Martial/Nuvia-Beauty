<?php

namespace App\Domains\Beauty\Models;

use App\Domains\Storage\Models\MediaAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Marvel\Database\Models\Profile;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;

class BeautySession extends Model
{
    public const STATE_DRAFT = 'draft';
    public const STATE_MEDIA_UPLOADED = 'media_uploaded';
    public const STATE_ANALYSIS_PENDING = 'analysis_pending';
    public const STATE_ANALYSIS_COMPLETED = 'analysis_completed';
    public const STATE_SAVED = 'saved';
    public const STATE_DISCARDED = 'discarded';
    public const STATE_FAILED = 'failed';

    protected $table = 'beauty_sessions';

    protected $guarded = [];

    protected $casts = [
        'saved_at' => 'datetime',
        'discarded_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }

    public function beautyProfile(): BelongsTo
    {
        return $this->belongsTo(BeautyProfile::class, 'beauty_profile_id');
    }

    public function currentSnapshot(): BelongsTo
    {
        return $this->belongsTo(BeautyProfileSnapshot::class, 'current_snapshot_id');
    }

    public function primaryMediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'primary_media_asset_id');
    }

    public function aiTasks(): HasMany
    {
        return $this->hasMany(BeautyAiTask::class, 'beauty_session_id');
    }

    public function analysisResults(): HasMany
    {
        return $this->hasMany(BeautyAnalysisResult::class, 'beauty_session_id');
    }

    public function quotaEvents(): HasMany
    {
        return $this->hasMany(BeautyQuotaEvent::class, 'beauty_session_id');
    }
}
