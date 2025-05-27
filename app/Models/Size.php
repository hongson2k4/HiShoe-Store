<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name'];
    
    public $timestamps = false;
    public function products() {
        return $this->hasMany(Products::class, 'size_id');
    }
}
