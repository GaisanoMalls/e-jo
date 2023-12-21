<?php

namespace App\Http\Traits;

use App\Models\Branch;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\PriorityLevel;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Suffix;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

trait BasicModelQueries
{
    public function queryServiceDepartments()
    {
        return ServiceDepartment::orderByDesc('created_at')->get();
    }

    public function queryHelpTopics()
    {
        return HelpTopic::orderByDesc('created_at')->get();
    }

    public function queryServiceLevelAgreements()
    {
        return ServiceLevelAgreement::orderByDesc('created_at')->get();
    }

    public function queryRoles()
    {
        return Role::orderBy('name', 'asc')->get();
    }

    public function querySuffixes(): Collection
    {
        return Suffix::all();
    }

    public function queryBranches()
    {
        return Branch::orderByDesc('created_at')->get();
    }

    public function queryBUDepartments()
    {
        return Department::orderByDesc('created_at')->get();
    }

    public function queryPriorityLevels()
    {
        return PriorityLevel::orderBy('id', 'asc')->get();
    }

    public function queryTeams()
    {
        return Team::orderByDesc('created_at')->get();
    }

    public function queryApprovers()
    {
        return User::approvers();
    }

    public function queryServiceDepartmentAdmins(): Collection|array
    {
        return User::serviceDepartmentAdmins();
    }

    public function queryAgents(): Collection|array
    {
        return User::agents();
    }

    public function queryUsers(): Collection|array
    {
        return User::requesters();
    }

    public function queryTags()
    {
        return Tag::orderByDesc('created_at')->get();
    }

    public function queryTicketStatus()
    {
        return Status::orderByDesc('created_at')->get();
    }
}
