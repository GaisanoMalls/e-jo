<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopicLevelApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_topic_id',
        'approval_level_id',
        'approver_id'
    ];

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function approvalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class);
    }
}