<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'name',
        'size'
    ];
    public function products_variant()
{
    return $this->hasMany(Products_variant::class, 'size_id');
}
}