<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCostingFile extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_costing_id', 'file_attachment'];

    public function ticketCosting()
    {
        return $this->belongsTo(TicketCosting::class);
    }
}
