<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpTopicApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_topic_configuration_id',
        'help_topic_id',
        'level',
        'user_id',
    ];


    public function configuration(): BelongsTo
    {
        return $this->belongsTo(HelpTopicConfiguration::class, 'help_topic_configuration_id');
    }

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }
}
