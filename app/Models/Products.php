<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
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
}
