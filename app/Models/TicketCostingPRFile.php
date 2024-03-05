<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCostingPRFile extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'ticket_costing_pr_files';
    protected $fillable = ['ticket_costing_id', 'file_attachment', 'is_approved_level_1_approver', 'is_approved_level_2_approver'];

    public function ticketCosting()
    {
        return $this->belongsTo(TicketCosting::class);
    }
}
