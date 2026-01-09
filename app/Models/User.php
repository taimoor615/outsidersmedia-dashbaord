<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_image',
        'timezone',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
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

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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

    // Scope for team members
    public function scopeClient($query)
    {
        return $query->where('role', 'client');
    }

    // Update last login timestamp
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
