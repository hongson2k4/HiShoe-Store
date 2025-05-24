<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'customer_reasons',
        'shipping_address',
        'voucher_id',
        'product_name_id',
        'notes',
        'needs_support',
        'needs_refunded',
        'order_check',
        'is_refunded',
        'is_reviewed',
        'order_check',
    ];

    /**
     * Determine if the order can be cancelled
     *
     * @return bool
     */
    public static function generateOrderCheck()
        {
            do {
                $code = Str::upper(Str::random(10)); // Sinh chuỗi ngẫu nhiên 10 ký tự
            } while (self::where('order_check', $code)->exists()); // Đảm bảo mã không trùng

            return $code;
        }

    public function canCancel()
    {
        // Chỉ cho phép hủy khi trạng thái là "Đơn đã đặt" (status = 1)
        $cancelableStatuses = [1]; // Chỉ Pending
    
        if (!in_array($this->status, $cancelableStatuses)) {
            return false;
        }
    
        // Kiểm tra thời gian 7 ngày (7 * 24 = 168 giờ)
        $createdWithin7Days = Carbon::parse($this->created_at)->diffInHours(Carbon::now()) <= 168;
    
        return $createdWithin7Days;
    }

    //Kiểm tra tạo đơn đã quá 7 ngày
    public function isOver7Days()
    {
        // Kiểm tra xem đơn hàng đã quá 7 ngày (7 * 24 = 168 giờ) hay chưa
        return Carbon::parse($this->created_at)->diffInHours(Carbon::now()) > 168;
    }

    /**
     * Get the list of possible order statuses
     *
     * @return array
     */
    public static function getStatusList()
    {
        return [
            1 => 'Đơn đã đặt',
            2 => 'Đang đóng gói',
            3 => 'Đang vận chuyển',
            4 => 'Đã giao hàng',
            5 => 'Đã hủy',
            6 => 'Đã trả hàng',
            7 => 'Đã nhận hàng', // Thêm trạng thái dã nhận được hàng
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
            4 => [6, 7], // Delivered can go to Refunded or Received
            5 => [],     // Cancelled cannot change
            6 => [],     // Refunded cannot change
            7 => [],     // Received cannot change
        ];

        return $statusTransitions[$this->status] ?? [];
    }

    /**
     * Get the current status text
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            1 => 'Đơn đã đặt',
            2 => 'Đang đóng gói',
            3 => 'Đang vận chuyển',
            4 => 'Đã giao hàng',
            5 => 'Đã hủy',
            6 => 'Đã trả hàng',
            7 => 'Đã nhận hàng', // Thêm trạng thái mới
        ];
        return $statuses[$this->status] ?? 'Không xác định';
    }
    
    /**
     * Get status badge class
     *
     * @return string
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            1 => 'bg-primary', // Pending
            2 => 'bg-warning',      // Processing
            3 => 'bg-info',   // Shipped
            4 => 'bg-success',   // Delivered
            5 => 'bg-danger',    // Cancelled
            6 => 'bg-secondary',      // Refunded
            7 => 'bg-primary',   // Received
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

     

    // Mối quan hệ với bảng order_item_histories
    public function orderItemHistories()
    {
        return $this->hasMany(OrderItemHistory::class, 'order_id', 'id');
    }

    // Quan hệ với bảng products
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
    
    public function variant()
    {
        return $this->belongsTo(Product_variant::class, 'variant_id');
    }

    // hiển thị màu sắc cho status client
    /**
     * Get status class for client
     *
     * @return string
     */
    public function getStatusClass()
    {
        return match ($this->status) {
            1 => 'bg-primary',   // Đơn đã đặt - Xanh dương
            2 => 'bg-warning',   // Đang đóng gói - Vàng
            3 => 'bg-info',      // Đang vận chuyển - Xanh nhạt
            4 => 'bg-success',   // Đã giao hàng - Xanh lá
            5 => 'bg-danger',    // Đã hủy - Đỏ
            6 => 'bg-secondary', // Đã trả hàng - Xám
            7 => 'bg-primary',   // Đã nhận hàng - Xanh dương
            default => 'bg-light text-dark',
        };
    }


}
