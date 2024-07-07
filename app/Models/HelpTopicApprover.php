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
        'user_id',
        'level',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(HelpTopicConfiguration::class, 'help_topic_configuration_id');
    }

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
