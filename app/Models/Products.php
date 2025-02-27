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

}
