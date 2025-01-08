<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationApprovalStatus extends Model
{
    use HasFactory;

    protected $table = 'recommendation_approval_status';
    protected $fillable = [
        'recommendation_id',
        'approval_status',
        'disapproved_reason',
    ];
    public $timestamps = false;

    public function recommendation(): BelongsTo
    {
        return $this->belongsTo(Recommendation::class);
    }
}
