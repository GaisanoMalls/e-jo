<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subteam extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'name'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function agents(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_subteams', 'subteam_id', 'user_id')
            ->role(Role::AGENT)
            ->withTimestamps();
    }
}
