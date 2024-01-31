<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCosting extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'amount'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fileAttachments()
    {
        return $this->hasMany(TicketCostingFile::class);
    }
}
