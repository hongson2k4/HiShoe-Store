<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_details extends Model
{
    use HasFactory;
    protected $table = 'product_details';
    protected $fillable = [
        'id',
        'product_id',
        'detail_title',
        'detail_content',
        'detail_image',
    ];
    public $timestamps = false;
    /**
     * Get the product that owns the product detail.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
