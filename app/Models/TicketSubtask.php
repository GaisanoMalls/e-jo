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
        'assignee_id',
        'task_name',
        'status',
    ];

    protected $casts = ['status' => SubtaskStatusEnum::class];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
