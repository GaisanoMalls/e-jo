<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTag extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'tag_id'];
    public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}