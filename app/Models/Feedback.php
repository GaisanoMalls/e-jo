<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rating',
        'had_issues_encountered',
        'description',
        'suggestion',
        'accepted_privacy_policy'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}