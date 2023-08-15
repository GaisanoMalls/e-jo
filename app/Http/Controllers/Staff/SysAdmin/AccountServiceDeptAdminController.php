<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreServiceDeptAdminRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateServiceDeptAdminRequest;
use App\Http\Traits\SlugGenerator;
use App\Http\Traits\UserDetails;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountServiceDeptAdminController extends Controller
{
    use SlugGenerator, UserDetails;

    public function store(StoreServiceDeptAdminRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'service_department_id' => $request->service_department,
                    'role_id' => Role::SERVICE_DEPARTMENT_ADMIN,
                    'email' => $request->email,
                    'password' => \Hash::make('departmentadmin')
                ]);

                Profile::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'suffix' => $request->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $request->first_name,
                        $request->middle_name,
                        $request->last_name,
                        $request->suffix
                    ]))
                ]);
            });

            return back()->with('success', 'You have successfully created a new department admin.');

        } catch (\Exception $e) {
            return back()->with('success', 'Failed to save a new service department admin.');
        }
    }

    public function viewDetails(User $serviceDeptAdmin)
    {
        return view(
            'layouts.staff.system_admin.manage.accounts.roles.details.service_department_admin_details',
            compact('serviceDeptAdmin')
        );
    }

    public function editDetails(User $serviceDeptAdmin)
    {
        $suffixes = $this->suffixes();
        $branches = $this->branches();
        $serviceDepartments = $this->serviceDepartments();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_service_dept_admin',
            compact([
                'serviceDeptAdmin',
                'suffixes',
                'branches',
                'serviceDepartments'
            ])
        );
    }

    public function update(UpdateServiceDeptAdminRequest $request, User $serviceDeptAdmin)
    {
        try {
            DB::transaction(function () use ($serviceDeptAdmin, $request) {
                $serviceDeptAdmin->update([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'service_department_id' => $request->service_department,
                    'email' => $request->email
                ]);

                $serviceDeptAdmin->profile()->update([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'suffix' => $request->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $request->first_name,
                        $request->middle_name,
                        $request->last_name,
                        $request->suffix
                    ]))
                ]);
            });

            return back()->with('success', "You have successfully updated the account for {$serviceDeptAdmin->profile->getFullName()}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update the service department admin. Please try again.');
        }
    }

    public function delete(User $serviceDeptAdmin)
    {
        try {
            $serviceDeptAdmin->delete();
            $serviceDeptAdmin->profile()->delete();

            return back()->with('success', 'Department admin successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the department admin.');
        }
    }

    public function branchBUDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }
}