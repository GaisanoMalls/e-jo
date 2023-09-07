<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTag extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'tag_id'];
    protected $table = 'ticket_tag';
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