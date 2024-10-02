<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IctRecommendationApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'ict_recommendation_id',
        'level',
    ];

    public $timestamps = false;

    public function ictRecommendation()
    {
        return $this->belongsTo(IctRecommendation::class);
    }

    public function approvers()
    {
        return $this->hasMany(IctRecommendationApprover::class, 'approval_level_id');
    }
}
