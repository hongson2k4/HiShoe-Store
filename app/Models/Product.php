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

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
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
