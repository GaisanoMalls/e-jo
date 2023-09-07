<?php

namespace App\Models;

use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'level_approver')
            ->whereHas('role', fn($query) => $query->where('role_id', Role::APPROVER))
            ->withPivot(['level_id', 'user_id', 'help_topic_id'])
            ->withTimestamps();
    }

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_level');
    }
}