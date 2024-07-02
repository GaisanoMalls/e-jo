<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCostingFile extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_costing_id', 'file_attachment'];

    public function ticketCosting(): BelongsTo
    {
        return $this->belongsTo(TicketCosting::class);
    }
}
