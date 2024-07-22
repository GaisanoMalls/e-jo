<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctRecommendationFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'ict_recommendation_id',
        'file_attachmentfile_attachment'
    ];

    public function ictRecommendation(): BelongsTo
    {
        return $this->belongsTo(IctRecommendation::class);
    }
}
