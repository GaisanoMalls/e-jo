<?php

namespace App\Models;

use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    public function approvers(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'level_approver')
            ->whereHas('role', fn($query) => $query->where('role_id', Role::APPROVER))
            ->withPivot(['level_id', 'user_id', 'help_topic_id'])
            ->withTimestamps();
    }

    public function helpTopics(): BelongsToMany
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_level');
    }
}
