<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCostingPRFile extends Model
{
    use HasFactory;

    protected $table = 'ticket_costing_pr_files';
    protected $fillable = ['ticket_costing_id', 'file_attachment'];

    public function ticketCosting()
    {
        return $this->belongsTo(TicketCosting::class);
    }
}
