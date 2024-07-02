<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApproverLevel extends Model
{
    use HasFactory;

    protected $table = 'approver_level';
    protected $fillable = [
        'user_id',
        'level_id',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
