<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctRecommendationApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_level_id',
        'approver_id'
    ];

    public $timestamps = false;

    public function approvalLevel(): BelongsTo
    {
        return $this->belongsTo(IctRecommendationApprovalLevel::class, 'approval_level_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
