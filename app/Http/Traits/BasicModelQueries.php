<?php

namespace App\Http\Traits;

use App\Models\ApprovalLevel;
use App\Models\Branch;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\PriorityLevel;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\Suffix;
use App\Models\Team;
use App\Models\User;

trait BasicModelQueries
{
    public function queryServiceDepartments()
    {
        return ServiceDepartment::orderBy('name', 'asc')->get();
    }

    public function queryLevelOfApprovals()
    {
        return ApprovalLevel::orderBy('description', 'asc')->get();
    }

    public function queryServiceLevelAgreements()
    {
        return ServiceLevelAgreement::orderBy('time_unit', 'asc')->get();
    }

    public function queryRoles()
    {
        return Role::orderBy('name', 'asc')->get();
    }

    public function querySuffixes()
    {
        return Suffix::all();
    }

    public function queryBranches()
    {
        return Branch::orderBy('name', 'asc')->get();
    }

    public function queryBUDepartments()
    {
        return Department::orderBy('name', 'asc')->get();
    }

    public function queryPriorityLevels()
    {
        return PriorityLevel::orderBy('id', 'asc')->get();
    }

    public function queryTeams()
    {
        return Team::orderBy('created_at', 'desc')->get();
    }

    public function queryApprovers()
    {
        return User::approvers();
    }

    public function queryServiceDepartmentAdmins()
    {
        return User::serviceDepartmentAdmins();
    }

    public function queryAgents()
    {
        return User::agents();
    }

    public function queryUsers()
    {
        return User::requesters();
    }

}