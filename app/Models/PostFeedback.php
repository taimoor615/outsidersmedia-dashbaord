<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'client_name',
        'feedback',
        'action',
        'is_client_feedback',
    ];

    protected $casts = [
        'is_client_feedback' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isClientFeedback(): bool
    {
        return $this->is_client_feedback;
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->is_client_feedback) {
            return $this->client_name ?? 'Client';
        }
        return $this->user->name ?? 'Unknown';
    }
}
