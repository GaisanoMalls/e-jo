<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserDetails;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    use UserDetails;

    public function index()
    {
        $approvers = User::with('branch')->where('role_id', Role::APPROVER)->take(5)->orderBy('created_at', 'desc')->get();
        $serviceDepartmentAdmins = User::with(['department', 'branch'])->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN)->take(5)->orderBy('created_at', 'desc')->get();
        $agents = User::with(['team', 'department', 'branch'])->where('role_id', Role::AGENT)->take(5)->orderBy('created_at', 'desc')->get();
        $users = User::with(['department', 'branch'])->where('role_id', Role::USER)->take(5)->orderBy('created_at', 'desc')->get();
        $roles = Role::orderBy('name', 'asc')->get();
        $branches = Branch::orderBy('name', 'asc')->get();

        return view(
            'layouts.staff.system_admin.manage.accounts.account_main',
            compact([
                'approvers',
                'serviceDepartmentAdmins',
                'agents',
                'users',
                'roles',
                'branches'
            ])
        );
    }

    public function approvers()
    {
        $approvers = User::where('role_id', Role::APPROVER)
            ->orderBy('created_at', 'desc')->get();
        $branches = $this->branches();

        return view(
            'layouts.staff.system_admin.manage.accounts.roles.approvers_list',
            compact([
                'approvers',
                'branches'
            ])
        );
    }

    public function serviceDepartmentAdmins()
    {
        $serviceDepartmentAdmins = User::where('role_id', Role::SERVICE_DEPARTMENT_ADMIN)
            ->orderBy('created_at', 'desc')->get();
        $branches = $this->branches();

        return view(
            'layouts.staff.system_admin.manage.accounts.roles.service_department_admins_list',
            compact([
                'serviceDepartmentAdmins',
                'branches'
            ])
        );
    }

    public function agents()
    {
        $agents = User::where('role_id', Role::AGENT)
            ->orderBy('created_at', 'desc')->get();
        $branches = $this->branches();

        return view(
            'layouts.staff.system_admin.manage.accounts.roles.agents_list',
            compact([
                'agents',
                'branches'
            ])
        );
    }

    public function users()
    {
        $users = User::where('role_id', Role::USER)
            ->orderBy('created_at', 'desc')->get();
        $branches = $this->branches();

        return view(
            'layouts.staff.system_admin.manage.accounts.roles.users_list',
            compact([
                'users',
                'branches'
            ])
        );
    }

}