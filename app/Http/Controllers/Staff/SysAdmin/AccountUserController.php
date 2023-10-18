<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreUserRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateUserRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccountUserController extends Controller
{
    use Utils, BasicModelQueries;

    public function store(StoreUserRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'department_id' => $request->department,
                    'branch_id' => $request->branch,
                    'role_id' => Role::USER,
                    'email' => $request->email,
                    'password' => \Hash::make('requester')
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

            return back()->with('success', 'Account successfully created');

        } catch (\Exception $e) {
            dd($e->getMessage());
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
        $suffixes = $this->querySuffixes();
        $branches = $this->queryBranches();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_user',
            compact([
                'user',
                'suffixes',
                'branches',
            ])
        );
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::transaction(function () use ($user, $request) {
                $user->update([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'email' => $request->email
                ]);

                $user->profile()->update([
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

            return back()->with('success', "You have successfully updated the account for {$user->profile->getFullName()}.");

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to update the account.');
        }
    }

    public function delete(User $user)
    {
        try {
            (!is_null($user->profile->picture))
                ? Storage::delete($user->profile->picture)
                : '';

            $user->delete();
            $user->profile()->delete();

            return back()->with('success', 'Account successfully deleted.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to delete the account.');
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