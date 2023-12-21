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

    public function approver()
    {
        return $this->hasOne(User::class);
    }

    public function helpTopics(): BelongsToMany
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_level');
    }
}
