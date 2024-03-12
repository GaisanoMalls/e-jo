<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSpecialProjectStatus extends Model
{
    use HasFactory;

    protected $table = 'ticket_special_project_status';
    protected $fillable = ['ticket_id', 'costing_and_planning_status', 'purchasing_status'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
