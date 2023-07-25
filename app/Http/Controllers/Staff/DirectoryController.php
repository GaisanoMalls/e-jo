<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{

    public function index()
    {
        $department_admins = User::with(['branch', 'department', 'role'])->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN)->get();

        return view('layouts.staff.directory.roles.department_admins', compact('department_admins'));
    }

    public function approvers()
    {
        $approvers = User::where('role_id', Role::APPROVER)->get();

        return view(
            'layouts.staff.directory.roles.approvers',
            compact([
                'approvers',
            ])
        );
    }

    public function agents()
    {
        $agents = User::with(['branch', 'department', 'serviceDepartment', 'role'])->where('role_id', Role::AGENT)->get();

        return view(
            'layouts.staff.directory.roles.agents',
            compact([
                'agents',
            ])
        );
    }

    public function requesters()
    {
        $requesters = User::with(['branch', 'department', 'serviceDepartment', 'role'])->where('role_id', Role::USER)->get();

        return view(
            'layouts.staff.directory.roles.requesters',
            compact([
                'requesters',
            ])
        );
    }

    public function teams()
    {
        $teams = Team::orderBy('created_at', 'desc')->get();
    }
}