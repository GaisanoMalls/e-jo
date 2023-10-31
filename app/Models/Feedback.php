<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
