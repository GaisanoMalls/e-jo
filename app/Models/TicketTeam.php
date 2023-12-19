<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTeam extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'team_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function team_id()
    {
        return $this->belongsTo(Team::class);
    }
}
