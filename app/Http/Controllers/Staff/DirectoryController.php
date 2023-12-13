<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;

class DirectoryController extends Controller
{
    use BasicModelQueries;

    public function index()
    {
        $departmentAdmins = $this->queryServiceDepartmentAdmins();
        return view('layouts.staff.directory.roles.department_admins', compact('departmentAdmins'));
    }

    public function approvers()
    {
        $approvers = $this->queryApprovers();
        return view('layouts.staff.directory.roles.approvers', compact('approvers'));
    }

    public function agents()
    {
        $agents = $this->queryAgents();
        return view('layouts.staff.directory.roles.agents', compact('agents'));
    }

    public function requesters()
    {
        $requesters = $this->queryUsers();
        return view('layouts.staff.directory.roles.requesters', compact('requesters'));
    }

    // public function teams()
    // {
    //     $teams = $this->queryTeams();
    // }
}