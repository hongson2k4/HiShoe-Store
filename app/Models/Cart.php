<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function variant()
    {
        return $this->belongsTo(Product_variant::class, 'product_variant_id');
    }
}