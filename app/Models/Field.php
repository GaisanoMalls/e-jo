<?php

namespace App\Models;

use App\Enums\FieldRequiredOptionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_required',
        'name',
        'label',
        'type',
        'variable_name',
    ];

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_field');
    }

    public function isRequired(): string
    {
        return $this->is_required ? 'Yes' : 'No';
    }
}
