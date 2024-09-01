<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['help_topic_id', 'visible_to', 'editable_to', 'name'];

    protected $casts = [
        'visible_to' => 'array',
        'editable_to' => 'array'
    ];

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(TicketCustomFormField::class);
    }
}
