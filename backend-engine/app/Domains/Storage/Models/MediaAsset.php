<?php

namespace App\Domains\Storage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAsset extends Model
{
    use SoftDeletes;

    protected $table = 'beauty_media_assets';

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
        'discarded_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
