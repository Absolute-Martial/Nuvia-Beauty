<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeautyAiTask extends Model
{
    protected $table = 'beauty_ai_tasks';

    protected $guarded = [];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'queued_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function beautySession(): BelongsTo
    {
        return $this->belongsTo(BeautySession::class, 'beauty_session_id');
    }
}
