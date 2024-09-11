<?php

namespace App\Models;

use App\Enums\FieldTypesEnum;
use App\Http\Traits\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldHeaderValue extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'ticket_id',
        'field_id',
        'value',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function formattedDate($value)
    {
        return $this->formatDate($value);
    }
}
