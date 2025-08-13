<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'min_order_value',
        'max_discount_value',
        'usage_limit',
        'used_count',
        'status'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_EXPIRED = 2;
    public const STATUS_FUTURE = 3;

    /** 
     * Get the orders associated with the voucher.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    /**
     * Scope a query to only include active vouchers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
    /**
     * Check if the voucher is valid
     */
    public function isValid(): bool
    {
        return $this->status == self::STATUS_ACTIVE
            && now()->between($this->start_date, $this->end_date)
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
    /**
     * Check if the voucher is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date < now();
    }
    /**
     * Apply the voucher to an order
     */
    public function applyToOrder(Order $order)
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($order->total_price < $this->minimum_order_value) {
            return false;
        }

        $order->total_price -= $this->discount_amount;
        $order->voucher_id = $this->id;

        $this->increment('used_count');

        return true;
    }
    /**
     * Get the discount amount based on the type
     */
    public function getStatusTextAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'Active' : 'Inactive';
    }
}
