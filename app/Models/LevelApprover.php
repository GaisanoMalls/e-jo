<?php

namespace App\Models;

use App\Models\HelpTopic;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelApprover extends Model
{
    use HasFactory;

    protected $table = 'level_approver';
    protected $fillable = [
        'level_id',
        'user_id',
        'help_topic_id',
        'approval_order',
        'is_done',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function helpTopic(): BelongsTo
    {
        return $this->belongsTo(HelpTopic::class, 'help_topic_id');
    }
}
