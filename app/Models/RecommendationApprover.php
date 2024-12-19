<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_level_id',
        'approver_id'
    ];

    public $timestamps = false;

    public function approvalLevel(): BelongsTo
    {
        return $this->belongsTo(RecommendationApprovalLevel::class, 'approval_level_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
