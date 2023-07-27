<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountServiceDeptAdminController extends Controller
{
    use SlugGenerator;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'bu_department' => ['required'],
            'service_department' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeServiceDeptAdmin')->withInput();

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'branch_id' => $request->input('branch'),
                    'department_id' => $request->input('bu_department'),
                    'service_department_id' => $request->input('service_department'),
                    'role_id' => Role::SERVICE_DEPARTMENT_ADMIN,
                    'email' => $request->input('email'),
                    'password' => \Hash::make('departmentadmin')
                ]);

                Profile::create([
                    'user_id' => $user->id,
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'suffix' => $request->input('suffix'),
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

    public function delete(User $departmentAdmin)
    {
        try {
            $departmentAdmin->delete();
            $departmentAdmin->profile()->delete();

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