<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_ship_address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receive_name',
        'receive_number',
        'receive_address',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}