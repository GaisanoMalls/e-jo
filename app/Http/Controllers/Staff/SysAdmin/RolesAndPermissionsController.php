<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolesAndPermissionsController extends Controller
{
    public function index()
    {
        $userQuery = User::with(['department', 'branch', 'role'])->orderBy('created_at', 'asc')->get();

        $users = $userQuery->where('role_id', Role::USER);
        $agents = $userQuery->where('role_id', Role::AGENT);
        $approvers = $userQuery->where('role_id', Role::APPROVER);
        $serviceDeptAdmins = $userQuery->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN);

        $allUsers = $userQuery;
        $profilePicLimit = (int) 5;

        return view(
            'layouts.staff.system_admin.manage.roles_and_permissions.roles_and_permissions_index',
            compact([
                'allUsers',
                'approvers',
                'serviceDeptAdmins',
                'agents',
                'users',
                'profilePicLimit'
            ])
        );
    }

}