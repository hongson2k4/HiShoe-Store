<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_variant extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_variants';
    protected $fillable = [
        'id',
        'product_id',
        'size_id',
        'color_id',
        'price',
        'stock_quantity',
        'image_url',
    ];
    public $timestamp = false;

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }


}
