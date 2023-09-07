<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopicLevel extends Model
{
    use HasFactory;

    protected $fillable = ['help_topic_id', 'level_id'];

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class, 'help_topic_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}