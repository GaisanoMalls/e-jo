<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCustomForm extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'custom_form'];

    protected $casts = [
        'custom_form' => AsArrayObject::class,
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
