<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Http\Traits\UserDetails;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountUserController extends Controller
{
    use SlugGenerator, UserDetails;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department' => ['required'],
            'branch' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeUser')->withInput();

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'department_id' => (int) $request->input('department'),
                    'branch_id' => (int) $request->input('branch'),
                    'role_id' => (int) Role::USER,
                    'email' => $request->input('email'),
                    'password' => \Hash::make('user')
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

            return back()->with('success', 'You have successfully created a new user/requester');

        } catch (\Exception $e) {
            return back()->with('success', 'Failed to save a new user/requester');
        }
    }

    public function viewDetails(User $user)
    {
        return view(
            'layouts.staff.system_admin.manage.accounts.roles.details.user_details',
            compact('user')
        );
    }

    public function editDetails(User $user)
    {
        $suffixes = $this->suffixes();
        $branches = $this->branches();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_user',
            compact([
                'user',
                'suffixes',
                'branches',
            ])
        );
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'bu_department' => ['required'],
            'branch' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'editUser')->withInput();

        try {
            DB::transaction(function () use ($user, $request) {
                $user->update([
                    'branch_id' => $request->input('branch'),
                    'department_id' => $request->input('bu_department'),
                    'email' => $request->input('email')
                ]);

                $user->profile()->update([
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

            return back()->with('success', "You have successfully updated the account for {$user->profile->getFullName()}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update the user. Please try again.');
        }
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
            $user->profile()->delete();

            return back()->with('success', 'User successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the user.');
        }
    }

    public function getBUDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }

    public function getServiceDepartments(Department $department)
    {
        return response()->json($department->serviceDepartments);
    }
}