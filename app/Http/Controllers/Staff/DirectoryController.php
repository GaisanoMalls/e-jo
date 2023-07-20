<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{

    public function index()
    {
        $department_admins = User::with(['branch', 'department', 'role'])->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN)->get();

        return view('layouts.staff.directory.directory_main', compact('department_admins'));
    }

    public function approvers()
    {
        $approvers = User::where('role_id', Role::APPROVER)->get();
        $department_admins = User::with(['branch', 'role'])->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN)->get();

        return view(
            'layouts.staff.directory.roles.approvers',
            compact([
                'approvers',
                'department_admins'
            ])
        );
    }

    public function agents()
    {
        $agents = User::with(['branch', 'department', 'serviceDepartment', 'role'])->where('role_id', Role::AGENT)->get();
        $department_admins = User::where('role_id', Role::SERVICE_DEPARTMENT_ADMIN)->get();

        return view(
            'layouts.staff.directory.roles.agents',
            compact([
                'agents',
                'department_admins'
            ])
        );
    }
}