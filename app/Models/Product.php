<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'brand_id',
        'color_id',
        'size_id',
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
        public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    // Quan hệ với bảng orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_name_id', 'id');
    }
    public function likes() {
        return $this->hasMany(Like::class);
    }
    // Quan hệ với bảng orders
   
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
