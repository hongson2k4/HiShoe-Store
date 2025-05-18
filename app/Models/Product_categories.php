<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_categories extends Model
{
    use HasFactory;
    protected $table = 'product_categories';
    protected $fillable = [
        'id',
        'product_id',
        'category_id',
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}