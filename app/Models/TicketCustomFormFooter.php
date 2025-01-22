<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCustomFormFooter extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'ticket_custom_form_footer';

    protected $fillable = [
        'ticket_id',
        'form_id',
        'requested_by',
        'noted_by',
        'approved_by',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function notedBy()
    {
        return $this->belongsTo(User::class, 'noted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
