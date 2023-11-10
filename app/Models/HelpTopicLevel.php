<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpTopicLevel extends Model
{
    use HasFactory;

    protected $fillable = ['help_topic_id', 'level_id'];

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class, 'help_topic_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
