<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_image',
        'timezone',
        'last_login_at',
        'verification_token',
        'email_verified_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if user is team member
    public function isTeam(): bool
    {
        return $this->role === 'team';
    }

    // Check if user is client
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    // Check if user is active
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Check if email is verified
    public function isVerified(): bool
    {
        return $this->is_verified && $this->email_verified_at !== null;
    }

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for verified users
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Scope for admins
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // Scope for team members
    public function scopeTeam($query)
    {
        return $query->where('role', 'team');
    }

    // Scope for clients
    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    // Update last login timestamp
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    // Generate verification token
    public function generateVerificationToken(): string
    {
        $token = Str::random(64);
        $this->update(['verification_token' => $token]);
        return $token;
    }

    // Verify email
    public function verifyEmail(): void
    {
        $this->update([
            'is_verified' => true,
            'email_verified_at' => now(),
            'verification_token' => null,
            'status' => 'active',
        ]);
    }

    // Get profile image URL
    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image && file_exists(public_path($this->profile_image))) {
            return asset($this->profile_image);
        }

        // Return default avatar with initials
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
