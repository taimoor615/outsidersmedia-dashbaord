<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'created_by',
        'post_type',
        'caption',
        'webpage_url',
        'facebook_message',
        'instagram_message',
        'linkedin_message',
        'twitter_message',
        'tiktok_message',
        'youtube_message',
        'google_post_type',
        'google_title',
        'google_button',
        'google_button_link',
        'offer_code',
        'offer_link',
        'offer_terms',
        'offer_start_date',
        'offer_end_date',
        'offer_start_time',
        'offer_end_time',
        'event_start_date',
        'event_end_date',
        'event_start_time',
        'event_end_time',
        'platforms',
        'scheduled_at',
        'published_at',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'platforms' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'offer_start_date' => 'date',
        'offer_end_date' => 'date',
        'event_start_date' => 'date',
        'event_end_date' => 'date',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)->orderBy('order');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(PostFeedback::class)->latest();
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Helper methods
    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'pending_approval', 'approved']);
    }

    public function canDelete(): bool
    {
        return in_array($this->status, ['draft', 'pending_approval']);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending_approval' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'scheduled' => 'bg-blue-100 text-blue-800',
            'published' => 'bg-purple-100 text-purple-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}
