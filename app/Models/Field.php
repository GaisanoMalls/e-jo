<?php

namespace App\Models;

use App\Enums\FieldRequiredOptionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'label',
        'type',
        'variable_name',
        'is_required',
        'is_enabled',
    ];

    protected $casts = [
        'is_required' => 'bool',
        'is_enabled' => 'bool'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_field');
    }

    public function isEnabled()
    {
        return $this->is_enabled;
    }
}
