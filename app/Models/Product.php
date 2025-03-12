<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'brand_id',
        'image_url'
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    
    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

// Size Model
class Size extends Model
{
    protected $fillable = ['name'];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

// Color Model
class Color extends Model
{
    protected $fillable = ['name'];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
