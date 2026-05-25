<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'order',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * For videos, return a streaming URL that sends proper Range headers so
     * the browser can seek to any position.  For images, falls back to the
     * normal storage URL.
     */
    public function getStreamUrlAttribute(): string
    {
        if ($this->isVideo()) {
            return route('media.stream', $this->id);
        }

        return asset('storage/' . $this->file_path);
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }
}
