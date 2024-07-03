<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'service_department_id',
        'service_dept_child_id',
        'name',
        'slug',
    ];

    public function serviceDepartment(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function serviceDepartmentChild(): BelongsTo
    {
        return $this->belongsTo(ServiceDepartmentChildren::class, 'service_dept_child_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function helpTopics(): HasMany
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function subteams(): HasMany
    {
        return $this->hasMany(Subteam::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'team_branch');
    }

    public function agents(): Builder|BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_team')->role(Role::AGENT)->withTimestamps();
    }

    public function getBranches(): string
    {
        $branchNames = [];

        foreach ($this->branches as $branch) {
            $branchNames[] = $branch->name;
        }

        if (!empty($branchNames)) {
            return implode(', ', $branchNames);
        }

        return '';
    }

    public function getTeamServiceDeptChildren(): string
    {
        $childrenNames = [];

        foreach ($this->serviceDepartmentChildren as $child) {
            $childrenNames[] = $child->name;
        }

        if (!empty($childrenNames)) {
            return implode(', ', $childrenNames);
        }

        return '';
    }

    public function getSubteams(): string
    {
        $subteams = [];

        foreach ($this->subteams as $subteam) {
            $subteams[] = $subteam->name;
        }

        if (!empty($subteams)) {
            return implode(', ', $subteams);
        }

        return '';
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
