<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCustomFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'form_id',
        'value',
        'name',
        'label',
        'type',
        'variable_name',
        'is_required',
        'is_enabled'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_enabled' => 'boolean'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function ticketCustomFormFiles(): HasMany
    {
        return $this->hasMany(TicketCustomFormFile::class);
    }
}
