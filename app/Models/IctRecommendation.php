<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'approver_id',
        'requested_by_agent_id',
        'is_requesting_ict_approval',
        'is_approved'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requestedByAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_agent_id')->role(Role::AGENT);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id')->role(Role::SERVICE_DEPARTMENT_ADMIN);
    }

    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }
}
