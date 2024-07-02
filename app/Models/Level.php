<?php

namespace App\Models;

use App\Models\HelpTopic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'description'];

    public function approvers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'approver_level');
    }

    public function helpTopics(): BelongsToMany
    {
        return $this->belongsToMany(HelpTopic::class);
    }

    public function getLevelDescription(): string
    {
        return $this->pluck('description')->implode(', ');
    }
}
