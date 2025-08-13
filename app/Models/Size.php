<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;
    /**
     * Get the products associated with the size.
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'size_id');
    }
}
