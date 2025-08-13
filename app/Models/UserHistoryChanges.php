<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistoryChanges extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $hidden = ['created_at'];
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'field_name',
        'old_value',
        'new_value',
        'change_by',
        'content',
        'updated_at',
    ];

    /**
     * Get the user that owns the history change.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Get the user who made the change.
     */
    public function changed_by()
    {
        return $this->belongsTo(User::class, 'change_by');
    }
    /**
     * Get the updated_at attribute.
     */
    public function setUpdatedAt($value)
    {
        $this->attributes['updated_at'] = $value;
    }
}
