<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'rating',
        'comment',
        'status'
    ];
    protected $casts = [
        'rating' => 'integer',
        'status' => 'integer'
    ];
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    /**
     * Get the user that owns the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the product that the review belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }
    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    /**
     * Get the human-readable status name of the review.
     */
    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default => 'Unknown'
        };
    }
    /**
     * Check if the review is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
    /**
     * Get the star rating representation of the review.
     */
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
    /**
     * Approve the review and set its status to approved.
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();
        return $this;
    }
    /**
     * Reject the review and set its status to rejected.
     */
    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
        $this->save();
        return $this;
    }
}