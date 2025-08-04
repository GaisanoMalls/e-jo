<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approver_id',
        'is_approved',
        'date_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'date_approved' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
