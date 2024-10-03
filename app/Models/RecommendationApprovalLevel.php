<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
        'level',
    ];

    public $timestamps = false;

    public function ictRecommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function approvers()
    {
        return $this->hasMany(RecommendationApprover::class, 'approval_level_id');
    }
}
