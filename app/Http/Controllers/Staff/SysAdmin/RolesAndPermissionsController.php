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

        return view(
            'layouts.staff.system_admin.manage.roles_and_permissions.roles_and_permissions_index',
            compact([
                'roles',
                'approvers',
                'serviceDeptAdmins',
                'agents',
                'users',
                'profilePicLimit'
            ])
        );
    }

}