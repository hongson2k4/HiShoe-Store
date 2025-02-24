<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'username',
        'password',
        'full_name',
        'avatar',
        'email',
        'phone_number',
        'address',
    ];
    public $timestamp = false;
}
