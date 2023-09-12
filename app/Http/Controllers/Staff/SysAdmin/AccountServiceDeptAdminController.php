<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreServiceDeptAdminRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateServiceDeptAdminRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountServiceDeptAdminController extends Controller
{
    use Utils, BasicModelQueries;

    public function store(StoreServiceDeptAdminRequest $request)
    {
        if ($request->service_departments[0] === null) {
            return back()->with('empty_service_departments', 'Service department field is required.')
                ->withInput();
        }

        $selectedServiceDepartments = $this->getSelectedValue($request->service_departments);
        $existingServiceDepartments = ServiceDepartment::whereIn('id', $selectedServiceDepartments)->pluck('id');

        if (count($existingServiceDepartments) !== count($selectedServiceDepartments)) {
            return back()->with('invalid_service_departments', 'Invalid service department.')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $existingServiceDepartments) {
                $serviceDeptAdmin = User::create([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'service_department_id' => $request->service_department,
                    'role_id' => Role::SERVICE_DEPARTMENT_ADMIN,
                    'email' => $request->email,
                    'password' => \Hash::make('departmentadmin')
                ]);

                Profile::create([
                    'user_id' => $serviceDeptAdmin->id,
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

                $serviceDeptAdmin->serviceDepartments()->attach($existingServiceDepartments);
            });

            return back()->with('success', 'Added');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to save a new service department admin.');
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
        $suffixes = $this->querySuffixes();
        $branches = $this->queryBranches();
        $serviceDepartments = $this->queryServiceDepartments();

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
        if ($request->service_departments[0] === null) {
            return back()->with('empty_service_departments', 'Service department field is required.')
                ->withInput();
        }

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

                $serviceDeptAdmin->serviceDepartments()->sync(
                    $this->getSelectedValue($request->service_departments)
                );
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

            return back()->with('success', 'Deleted');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the service department admin.');
        }
    }

    public function branchBUDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }
}