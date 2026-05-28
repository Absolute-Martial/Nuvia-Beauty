<?php

namespace App\Domains\Beauty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeautyAnalysisResult extends Model
{
    protected $table = 'beauty_analysis_results';

    protected $guarded = [];

    protected $casts = [
        'summary_payload' => 'array',
        'normalized_traits' => 'array',
        'completed_at' => 'datetime',
    ];

    public function beautySession(): BelongsTo
    {
        return $this->belongsTo(BeautySession::class, 'beauty_session_id');
    }

    public function beautyAiTask(): BelongsTo
    {
        return $this->belongsTo(BeautyAiTask::class, 'beauty_ai_task_id');
    }
}
