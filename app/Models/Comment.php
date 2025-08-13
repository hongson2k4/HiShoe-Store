<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'content',
        'rating',
        'parent_id',
        'status',
    ];

    // Liên kết tới sản phẩm
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id'); // không phải 'products_id'
    }


    // Liên kết tới user (nếu có)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Bình luận cha
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Trả lời (bình luận con)
    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
