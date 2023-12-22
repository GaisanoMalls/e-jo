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

    protected $fillable = ['value', 'description'];

    public function approvers()
    {
        return $this->belongsToMany(User::class, 'approver_level');
    }

    public function helpTopics()
    {
        return $this->belongsToMany(HelpTopic::class);
    }

    public function getLevelDescription()
    {
        return $this->pluck('description')->implode(', ');
    }
}
