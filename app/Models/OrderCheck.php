<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCheck extends Model {
    use HasFactory;
    
    protected $table = 'orders'; // Đảm bảo đúng với tên trong database
    protected $fillable = ['order_check', 'status'];
}


?>