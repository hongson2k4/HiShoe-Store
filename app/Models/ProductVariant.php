<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    // Table name (explicitly defined, though Laravel would infer this)
    protected $table = 'product_variants';

    // Fillable fields matching the database schema
    protected $fillable = [
        'product_id', 
        'size_id', 
        'color_id', 
        'price', 
        'stock_quantity'
    ];

    // Cast numeric fields
    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer'
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Accessor for full variant name
    public function getFullNameAttribute(): string
    {
        return sprintf(
            "%s - %s - %s", 
            $this->product->name ?? 'Unknown Product',
            $this->size->name ?? 'Unknown Size',
            $this->color->name ?? 'Unknown Color'
        );
    }

    // Check if variant is in stock
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    // Decrease stock quantity
    public function decreaseStock(int $quantity): bool
    {
        if ($this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);
            return true;
        }
        return false;
    }

    // Get available stock
    public function getAvailableStockAttribute(): int
    {
        return $this->stock_quantity;
    }

    // Static method to find variant by product, size, and color
    public static function findVariant($productId, $sizeId, $colorId)
    {
        return self::where([
            'product_id' => $productId,
            'size_id' => $sizeId,
            'color_id' => $colorId
        ])->first();
    }
}