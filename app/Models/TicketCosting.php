<?php

namespace App\Models;

use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCosting extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['ticket_id', 'amount'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fileAttachments()
    {
        return $this->hasMany(TicketCostingFile::class);
    }

    public function getAmount()
    {
        return number_format($this->amount, 2, '.', ',');
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }
}
