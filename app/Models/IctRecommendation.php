<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IctRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'current_team_id',
        'is_approved'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function ictRecommendationFiles(): HasMany
    {
        return $this->hasMany(IctRecommendationFile::class);
    }
}
