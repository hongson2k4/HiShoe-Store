<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = ['user_id', 'product_id', 'order_id', 'rating', 'comment'];

    // Cast numeric fields
    protected $casts = [
        'rating' => 'integer',
        // 'status' => 'integer'
    ];

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    // Relationships

    // Scopes
    // public function scopeApproved($query)
    // {
    //     return $query->where('status', self::STATUS_APPROVED);
    // }

    // Accessors
    // public function getStatusNameAttribute(): string
    // {
    //     return match($this->status) {
    //         self::STATUS_PENDING => 'Pending',
    //         self::STATUS_APPROVED => 'Approved',
    //         self::STATUS_REJECTED => 'Rejected',
    //         default => 'Unknown'
    //     };
        
    // }
    

    // Check if review is approved
    // public function isApproved(): bool
    // {
    //     return $this->status === self::STATUS_APPROVED;
    // }

    // Get star rating display
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    // Method to approve review
    // public function approve()
    // {
    //     $this->status = self::STATUS_APPROVED;
    //     $this->save();
    //     return $this;
    // }

    // Method to reject review
    // public function reject()
    // {
    //     $this->status = self::STATUS_REJECTED;
    //     $this->save();
    //     return $this;
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public static function canReview($userId, $orderId)
{
    return !self::where('user_id', $userId)
        ->where('order_id', $orderId)
        ->exists();
}
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}