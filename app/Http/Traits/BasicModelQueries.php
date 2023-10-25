<?php

namespace App\Http\Traits;

use App\Models\ApprovalLevel;
use App\Models\Branch;
use App\Models\Department;
use App\Models\PriorityLevel;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Suffix;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;

trait BasicModelQueries
{
    public function queryServiceDepartments()
    {
        return ServiceDepartment::orderBy('created_at', 'desc')->get();
    }

    public function queryLevelOfApprovals()
    {
        return ApprovalLevel::orderBy('description', 'asc')->get();
    }

    public function queryServiceLevelAgreements()
    {
        return ServiceLevelAgreement::orderBy('created_at', 'desc')->get();
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
        return Branch::orderBy('created_at', 'desc')->get();
    }

    public function queryBUDepartments()
    {
        return Department::orderBy('created_at', 'desc')->get();
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

    public function queryTags()
    {
        return Tag::orderBy('created_at', 'desc')->get();
    }

    public function queryTicketStatus()
    {
        return Status::orderBy('created_at', 'desc')->get();
    }
}