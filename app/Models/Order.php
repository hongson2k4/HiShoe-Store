<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address',
        'voucher_id',
        'payment_method',
        'shipping_method',
        'notes'
    ];

    /**
     * Determine if the order can be cancelled
     *
     * @return bool
     */
    public function canCancel()
    {
        // Only pending and processing orders can be cancelled
        $cancelableStatuses = [1, 2]; // Pending and Processing

        // Check if order is in cancelable status
        if (!in_array($this->status, $cancelableStatuses)) {
            return false;
        }

        // Optional: Add time-based cancellation restriction
        // For example, can only cancel within 24 hours of order creation
        $createdWithin24Hours = Carbon::parse($this->created_at)->diffInHours(Carbon::now()) <= 24;

        return $createdWithin24Hours;
    }

    /**
     * Get the list of possible order statuses
     *
     * @return array
     */
    public static function getStatusList()
    {
        return [
            1 => 'Pending',
            2 => 'Processing',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Cancelled',
            6 => 'Refunded'
        ];
    }

    /**
     * Check if order status can be changed
     * 
     * @return bool
     */
    public function canChangeStatus()
    {
        // Statuses that can still be modified
        $modifiableStatuses = [1, 2, 3, 4]; // Pending, Processing, Shipped, Delivered
        return in_array($this->status, $modifiableStatuses);
    }

    /**
     * Get allowed next statuses
     * 
     * @return array
     */
    public function getAllowedNextStatuses()
    {
        $statusTransitions = [
            1 => [2, 5], // Pending can go to Processing or Cancelled
            2 => [3, 5], // Processing can go to Shipped or Cancelled
            3 => [4, 5], // Shipped can go to Delivered or Cancelled
            4 => [6],    // Delivered can go to Refunded
            5 => [],     // Cancelled cannot change
            6 => []      // Refunded cannot change
        ];

        return $statusTransitions[$this->status] ?? [];
    }

    /**
     * Get the current status text
     *
     * @return string
     */
    public function getStatusTextAttribute() //trả trạng thái về theo id
    {
        $statuses = [
            1 => 'Đơn đã đặt',
            2 => 'Đang đóng gói',
            3 => 'Đang vận chuyển',
            4 => 'Đã giao hàng',
            5 => 'Đã hủy',
            6 => 'Đã trả hàng',
        ];
        return $statuses[$this->status] ?? 'Không xác định';
    }
    

    public function getStatusBadgeClass()
    {
        $classes = [
            1 => 'bg-secondary', // Pending
            2 => 'bg-info',      // Processing
            3 => 'bg-warning',   // Shipped
            4 => 'bg-success',   // Delivered
            5 => 'bg-danger',    // Cancelled
            6 => 'bg-dark'       // Refunded
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Order Details
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Relationship with Voucher (optional)
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Method to apply voucher to order
    public function applyVoucher(Voucher $voucher)
    {
        if ($voucher->isValid()) {
            $voucher->applyToOrder($this);
            $this->save();
            return true;
        }
        return false;
    }

    // Recalculate total price with voucher consideration
    public function recalculateTotalPrice()
    {
        $total = $this->orderDetails->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });

        // Apply voucher discount if exists
        if ($this->voucher && $this->voucher->exists) {
            $total -= $this->voucher->discount_amount ?? 0;
        }

        $this->total_price = max(0, $total);
        $this->save();

        return $this->total_price;
    }

        public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

        public function orderItemHistories()
    {
        return $this->hasMany(OrderItemHistory::class, 'order_id', 'id');
    }

    // hiển thị màu sắc cho status
    public function getStatusClass()
    {
        return match ($this->status) {
            1 => 'bg-primary',   // Đơn đã đặt - Xanh dương
            2 => 'bg-warning',   // Đang đóng gói - Vàng
            3 => 'bg-info',      // Đang vận chuyển - Xanh nhạt
            4 => 'bg-success',   // Đã giao hàng - Xanh lá
            5 => 'bg-danger',    // Đã hủy - Đỏ
            6 => 'bg-secondary', // Đã trả hàng - Xám
            default => 'bg-light text-dark',
        };
    }


}
