<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproverLevel extends Model
{
    use HasFactory;

    protected $table = 'approver_level';
    protected $fillable = [
        'user_id',
        'level_id',
        'is_current',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function isCurrent()
    {
        return $this->is_current === 1;
    }
}
