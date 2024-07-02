<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketTeam extends Model
{
    use HasFactory;

    protected $table = 'ticket_team';

    protected $fillable = ['ticket_id', 'team_id'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function team_id(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
