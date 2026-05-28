<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeautyQuotaEvent extends Model
{
    protected $table = 'beauty_quota_events';

    protected $guarded = [];

    protected $casts = [
        'metadata_json' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function quotaAccount(): BelongsTo
    {
        return $this->belongsTo(BeautyQuotaAccount::class, 'beauty_quota_account_id');
    }

    public function beautySession(): BelongsTo
    {
        return $this->belongsTo(BeautySession::class, 'beauty_session_id');
    }
}
