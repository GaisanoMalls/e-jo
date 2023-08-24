<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'level_approver', 'level_id', 'user_id')
            ->whereHas('role', function ($query) {
                $query->where('role_id', Role::APPROVER);
            });
    }

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_level');
    }
}