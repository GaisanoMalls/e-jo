<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory, Utils;

    protected $fillable = ['name', 'slug'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_branch');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_branch');
    }

    public function approvers(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branch')->role(Role::APPROVER);
    }

    public function dateCreated(): string
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated(): string
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}
