<?php

namespace App\Models;

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
    ];

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_field');
    }
}
