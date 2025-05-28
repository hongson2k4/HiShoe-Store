<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'sku_code',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'brand_id',
        'image_url',
    ];
    public $timestamp = false;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function variants()
    {
        return $this->hasMany(Product_variant::class, 'product_id');
    }
    public function sizes()
    {
        return $this->hasManyThrough(Size::class, Product_variant::class, 'product_id', 'id', 'id', 'size_id');
    }
    public function colors()
    {
        return $this->hasManyThrough(Color::class, Product_variant::class, 'product_id', 'id', 'id', 'color_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id'); // đảm bảo đúng tên cột
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_name_id', 'id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'product_id'); // Explicitly specify the foreign key
    }
    // Quan hệ với bảng orders

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function product_categories()
    {
        return $this->hasMany(Product_categories::class, 'product_id');
    }
    public function productDetails()
    {
        return $this->hasMany(Product_details::class, 'product_id');
    }
    function generateSku($length = 12)
    {
        $length = max(12, min($length, 15)); // Giới hạn độ dài từ 12 đến 15
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomCode = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomCode;
    }
    public function syncStockQuantity()
    {
        $total = $this->variants()->sum('stock_quantity');
        $this->stock_quantity = $total;
        $this->save();
    }

}
