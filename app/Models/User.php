<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Fillable fields based on the SQL dump
    protected $fillable = [
        'username', 
        'password', 
        'full_name', 
        'avatar', 
        'email', 
        'phone_number', 
        'address', 
        'role', 
        'status'
    ];

    // Hidden fields for security
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attribute casting
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'integer',
        'status' => 'integer'
    ];

    // Role constants
    const ROLE_ADMIN = 1;
    const ROLE_USER = 0;

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    // Accessor for role name
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_USER => 'User',
            default => 'Unknown'
        };
    }

    // Accessor for status name
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            default => 'Unknown'
        };
    }

    // Check if user is an admin
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Generate a display name
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?? $this->username ?? $this->email;
    }

    // Avatar URL accessor
    public function getAvatarUrlAttribute(): string
    {
        // Default avatar if none is set
        if (!$this->avatar) {
            return $this->generateDefaultAvatar();
        }

        // Assuming avatars are stored in public storage
        return asset('storage/avatars/' . $this->avatar);
    }

    // Generate a default avatar
    protected function generateDefaultAvatar(): string
    {
        $name = $this->display_name;
        $initials = Str::upper(Str::substr($name, 0, 2));
        
        // Generate a placeholder avatar URL or use a default image
        return "https://ui-avatars.com/api/?name={$initials}&background=random";
    }

    // Method to update profile
    public function updateProfile(array $data)
    {
        $this->fill($data);
        
        // Only update password if provided
        if (!empty($data['password'])) {
            $this->password = bcrypt($data['password']);
        }

        $this->save();
        return $this;
    }

    // Total orders count
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }
}