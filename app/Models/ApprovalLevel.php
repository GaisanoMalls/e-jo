<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'description'];

    public function helpTopicLevelApprovers()
    {
        return $this->hasMany(HelpTopicLevelApprover::class);
    }

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_level_approvers', 'approval_level_id', 'help_topic_id')
            ->using(HelpTopicLevelApprover::class)
            ->withTimestamps();
    }
}