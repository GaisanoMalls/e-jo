<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HelpTopicConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_topic_id',
        'bu_department_id',
        'level_of_approval'
    ];

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function buDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'bu_department_id');
    }

    public function approvers(): HasMany
    {
        return $this->hasMany(HelpTopicApprover::class, 'help_topic_configuration_id');
    }
}
