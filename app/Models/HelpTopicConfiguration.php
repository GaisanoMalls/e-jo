<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopicConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_topic_id',
        'bu_department_id',
        'bu_department_name',
        'approvers_count',
    ];

    public function approvers()
    {
        return $this->hasMany(HelpTopicApprover::class, 'help_topic_configuration_id');
    }

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }
}
