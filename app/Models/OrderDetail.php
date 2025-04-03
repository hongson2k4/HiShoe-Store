<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'product_variant_id', 
        'quantity', 
        'price',
        'subtotal',
        'discount_amount'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
        'subtotal' => 'float',
        'discount_amount' => 'float'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(Product_variant::class);
    }

    // Sửa lại quan hệ product để trỏ trực tiếp đến Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Bỏ getSubtotalAttribute nếu bạn đã lưu subtotal trong cơ sở dữ liệu
    // Nếu không lưu, bạn có thể giữ lại để tính toán
    // public function getSubtotalAttribute(): float
    // {
    //     return $this->quantity * $this->price;
    // }

    // Mutators
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = max(1, intval($value));
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = round(floatval($value), 2);
    }

    // Scopes
    public function scopeExpensiveItems($query, $threshold = 100)
    {
        return $query->where('price', '>=', $threshold);
    }

    // Helper methods
    public function calculateTotalWithDiscount(): float
    {
        $subtotal = $this->subtotal;
        return $subtotal - ($this->discount_amount ?? 0);
    }

    public function getProductName(): string
    {
        return $this->productVariant->product->name ?? 'Unknown Product';
    }

    public function getVariantDetails(): string
    {
        $variant = $this->productVariant;
        return $variant ? implode(' - ', array_filter([
            $variant->color->name ?? '',
            $variant->size->name ?? ''
        ])) : 'No Variant Details';
    }

    // Static methods
    public static function getTotalQuantityForOrder($orderId)
    {
        return static::where('order_id', $orderId)->sum('quantity');
    }
}