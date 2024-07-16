<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketApproval extends Model
{
    use HasFactory;

    protected $table = 'ticket_approval';
    protected $fillable = [
        'ticket_id',
        'help_topic_approver_id',
        'is_approved'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function helpTopicApprover(): BelongsTo
    {
        return $this->belongsTo(HelpTopicApprover::class);
    }
}
