<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCustomFormFile extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_custom_form_field_id', 'file_attachment'];

    public function ticketCustomFormField(): BelongsTo
    {
        return $this->belongsTo(TicketCustomFormField::class);
    }
}
