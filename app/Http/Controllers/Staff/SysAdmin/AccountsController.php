<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function index()
    {
        $approvers = User::with('branch')->where('role_id', Role::APPROVER)->take(7)->orderBy('created_at', 'desc')->get();
        $departmentAdmins = User::with(['department', 'branch'])->where('role_id', Role::DEPARTMENT_ADMIN)->take(7)->orderBy('created_at', 'desc')->get();
        $agents = User::with(['team', 'department', 'branch'])->where('role_id', Role::AGENT)->take(7)->orderBy('created_at', 'desc')->get();
        $users = User::with(['department', 'branch'])->where('role_id', Role::USER)->take(7)->orderBy('created_at', 'desc')->get();
        $roles = Role::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('name', 'asc')->get();
        $branches = Branch::orderBy('name', 'asc')->get();

        return view('layouts.staff.system_admin.manage.accounts.account_main',
            compact([
                'approvers',
                'departmentAdmins',
                'agents',
                'users',
                'roles',
                'departments',
                'branches'
            ])
        );
    }

}
