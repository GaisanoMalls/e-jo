<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopicField extends Model
{
    use HasFactory;


    protected $table = 'help_topic_field';
    protected $fillable = [
        'help_topic_id',
        'field_id',
    ];

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
