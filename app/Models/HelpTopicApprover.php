<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopicApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_topic_id',
        'approver_id'
    ];

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class);
    }
}
