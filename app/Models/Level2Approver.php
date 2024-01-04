<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level2Approver extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function approver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
