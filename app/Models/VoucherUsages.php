<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherUsages extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'voucher_usages';

    protected $fillable = [
        'voucher_id',
        'user_id',
        'used_at'
    ];

    protected $dates = [
        'used_at'
    ];

}