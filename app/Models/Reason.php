<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'description'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}