<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'url', 'title', 'thumbnail', 'format', 'quality',
        'status', 'progress', 'file_path', 'file_size',
        'error_message', 'is_playlist', 'playlist_count', 'playlist_current'
    ];

    protected $casts = [
        'is_playlist' => 'boolean',
        'progress' => 'integer',
    ];
}
