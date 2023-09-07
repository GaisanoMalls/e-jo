<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFile extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'file_attachment'];
    public $timestamps = false;

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}