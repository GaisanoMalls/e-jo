<?php

namespace App\Models;

use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, Utils;

    protected $fillable = [
        'service_department_id',
        'name',
        'slug'
    ];

    public function serviceDepartment()
    {
        return $this->belongsTo(ServiceDepartment::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function helpTopics()
    {
        return $this->hasMany(HelpTopic::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'team_branch');
    }

    public function agents()
    {
        return $this->belongsToMany(User::class, 'user_team')
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->wherePivot(['user_id', 'team_id'])
            ->withTimestamps();
    }

    public function getBranches()
    {
        $branchNames = [];

        foreach ($this->branches as $branch) {
            array_push($branchNames, $branch->name);
        }

        if (!empty($branchNames)) {
            return implode(', ', $branchNames);
        }

        return '----';
    }

    public function dateCreated()
    {
        return $this->createdAt($this->created_at);
    }

    public function dateUpdated()
    {
        return $this->updatedAt($this->created_at, $this->updated_at);
    }
}