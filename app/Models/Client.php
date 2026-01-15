<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'website_url',
        'location',
        'business_description',
        'unique_value',
        'target_audience',
        'social_goals',
        'brand_tone',
        'content_types',
        'content_to_avoid',
        'preferred_cta',
        'share_third_party_content',
        'keywords',
        'competitors',
        'brand_assets_link',
        'timezone',
        'posting_days',
        'needs_approval',
        'approval_emails',
        'additional_notes',
        'plan_type',
        'posts_per_month',
        'networks',
        'status',
        'created_by',
        'share_token',
    ];

    protected $casts = [
        'social_goals' => 'array',
        'brand_tone' => 'array',
        'content_types' => 'array',
        'posting_days' => 'array',
        'networks' => 'array',
        'share_third_party_content' => 'boolean',
        'needs_approval' => 'boolean',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Boot method to generate share token
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->share_token)) {
                $client->share_token = Str::random(16);
            }
        });
    }

    // Check if client is active
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Get share URL
    public function getShareUrlAttribute(): string
    {
        return route('client.view', ['token' => $this->share_token]);
    }

    // Scope for active clients
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get plan details
    public function getPlanDetailsAttribute(): array
    {
        $plans = [
            'starter' => ['posts' => 8, 'networks' => 2, 'price' => 359],
            'business' => ['posts' => 12, 'networks' => 4, 'price' => 539],
            'scale' => ['posts' => 16, 'networks' => 2, 'price' => 659],
        ];

        return $plans[$this->plan_type] ?? $plans['starter'];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
