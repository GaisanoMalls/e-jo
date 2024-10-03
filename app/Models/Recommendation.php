<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'requested_by_sda_id', // service department admin
        'is_requesting_ict_approval',
        'reason',
        'is_approved'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requestedByServiceDeptAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_sda_id')->role(Role::SERVICE_DEPARTMENT_ADMIN);
    }

    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function approvalLevels(): HasMany
    {
        return $this->hasMany(RecommendationApprovalLevel::class, 'recommendation_id');
    }
}
