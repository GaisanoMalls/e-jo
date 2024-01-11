<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsController extends Controller
{
    public function __invoke()
    {
        $roles = Role::all();

        $users = User::role(Role::USER)->get();
        $agents = User::role(Role::AGENT)->get();
        $approvers = User::role(Role::APPROVER)->get();
        $serviceDeptAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)->get();
        $profilePicLimit = 5;

        $restApproverAccounts = $approvers->count() - $approvers->take($profilePicLimit)->count();
        $restServiceDeptAdminAccounts = $serviceDeptAdmins->count() - $serviceDeptAdmins->take($profilePicLimit)->count();
        $restAgentAccounts = $agents->count() - $agents->take($profilePicLimit)->count();
        $restUserAccounts = $users->count() - $users->take($profilePicLimit)->count();

        return view(
            'layouts.staff.system_admin.manage.roles_and_permissions.roles_and_permissions_index',
            compact([
                'roles',
                'approvers',
                'serviceDeptAdmins',
                'agents',
                'users',
                'profilePicLimit',
                'restApproverAccounts',
                'restServiceDeptAdminAccounts',
                'restAgentAccounts',
                'restUserAccounts',
            ])
        );
    }

}