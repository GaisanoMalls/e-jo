<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilledTicketForm extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'helpTopicForm'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    protected $casts = [
        'helpTopicForm' => AsArrayObject::class,
    ];
}
