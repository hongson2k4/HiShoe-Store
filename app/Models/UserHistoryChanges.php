<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistoryChanges extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'field_name',
        'old_value',
        'new_value',
        'change_by',
        'content',
        'change_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
