<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpTopicNonConfig extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'help_topic_non_config';
    protected $fillable = ['help_topic_id', 'bu_department_id'];

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class);
    }

    public function buDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'bu_department_id');
    }
}
