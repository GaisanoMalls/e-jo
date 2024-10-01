<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctRecommendationApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'ict_recommendation_approval_level_id',
        'approver_id'
    ];

    public function approvalLevel(): BelongsTo
    {
        return $this->belongsTo(IctRecommendationApprovalLevel::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
