<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    protected $casts = [
        'status' => 'integer'
    ];
    /**
     * Scope a query to only include active brands.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    /**
     * Get the human-readable status name of the brand.
     */
    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            default => 'Unknown'
        };
    }
    /**
     * Get the slug for the brand.
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }
    /**
     * Check if the brand is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    /**
     * Activate the brand and set its status to active.
     */
    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
        return $this;
    }
    /**
     * Deactivate the brand and set its status to inactive.
     */
    public function deactivate()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
        return $this;
    }
    /**
     * Find or create a brand by name.
     *
     * @param string $name The name of the brand to find or create.
     * @param array $additionalData Additional data to merge with the default values.
     * @return Brand
     */
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