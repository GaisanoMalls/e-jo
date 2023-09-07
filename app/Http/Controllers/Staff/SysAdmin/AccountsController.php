<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;
use App\Models\Role;
use App\Models\User;

class AccountsController extends Controller
{
    use BasicModelQueries;

    public function index()
    {
        $roles = $this->queryRoles();
        $branches = $this->queryBranches();
        $buDepartments = $this->queryBUDepartments();

        $approvers = User::with('branch')
            ->whereHas('role', fn($approver) => $approver->where('role_id', Role::APPROVER))
            ->take(5)->orderBy('created_at', 'desc')->get();

        $serviceDepartmentAdmins = User::with(['department', 'branch'])
            ->whereHas('role', fn($serviceDepartmentAdmin) => $serviceDepartmentAdmin->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN))
            ->take(5)->orderBy('created_at', 'desc')->get();

        $agents = User::with(['department', 'branch'])
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->take(5)->orderBy('created_at', 'desc')->get();

        $users = User::with(['department', 'branch'])
            ->whereHas('role', fn($user) => $user->where('role_id', Role::USER))
            ->take(5)->orderBy('created_at', 'desc')->get();

        return view(
            'layouts.staff.system_admin.manage.accounts.account_main',
            compact([
                'approvers',
                'serviceDepartmentAdmins',
                'agents',
                'users',
                'roles',
                'branches',
                'buDepartments'
            ])
        );
    }

    public function approvers()
    {
        $approvers = $this->queryApprovers();
        $branches = $this->queryBranches();
        $buDepartments = $this->queryBUDepartments();

        return view(
            'layouts.staff.system_admin.manage.accounts.roles.approvers_list',
            compact([
                'approvers',
                'branches',
                'buDepartments'
            ])
        );
    }

    public function serviceDepartmentAdmins()
    {
        $serviceDepartmentAdmins = $this->queryServiceDepartmentAdmins();
        $branches = $this->queryBranches();

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
        $agents = $this->queryAgents();
        $branches = $this->queryBranches();

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
        $users = $this->queryUsers();
        $branches = $this->queryBranches();

        return view(
            'layouts.staff.system_admin.manage.accounts.roles.users_list',
            compact([
                'users',
                'branches'
            ])
        );
    }

}