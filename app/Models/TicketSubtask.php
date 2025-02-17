<?php

namespace App\Models;

use App\Enums\SubtaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketSubtask extends Model
{
    // * TICKET SUBTASK IS CURRENTLY IN PENDING STATE
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'team_id',
        'agent_id',
        'name',
        'status',
    ];

    protected $casts = ['status' => SubtaskStatusEnum::class];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
