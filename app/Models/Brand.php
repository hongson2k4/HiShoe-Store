<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = [
        'name', 
        'description', 
        'status'
    ];

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Casts for type handling
    protected $casts = [
        'status' => 'integer'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Accessors
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            default => 'Unknown'
        };
    }

    // Generate a URL-friendly slug
    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }

    // Check if brand is active
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Method to activate brand
    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
        return $this;
    }

    // Method to deactivate brand
    public function deactivate()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
        return $this;
    }

    // Static method to find or create brand
    public static function findOrCreateByName(string $name, array $additionalData = [])
    {
        return self::firstOrCreate(
            ['name' => $name],
            array_merge([
                'description' => '',
                'status' => self::STATUS_ACTIVE
            ], $additionalData)
        );
    }
}