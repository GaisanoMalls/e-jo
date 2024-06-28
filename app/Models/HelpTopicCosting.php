<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpTopicCosting extends Model
{
    use HasFactory;


    protected $fillable = [
        'help_topic_id',
        'costing_approvers',
        'amount',
        'final_costing_approvers',
    ];

    protected $casts = [
        'costing_approvers' => 'array',
        'final_costing_approvers' => 'array',
    ];

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }
}
