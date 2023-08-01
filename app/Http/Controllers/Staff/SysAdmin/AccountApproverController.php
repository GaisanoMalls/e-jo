<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Http\Traits\UserDetials;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountApproverController extends Controller
{
    use SlugGenerator, UserDetials;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'bu_department' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80'],
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeApprover')->withInput();

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'branch_id' => $request->input('branch'),
                    'department_id' => $request->input('bu_department'),
                    'service_department_id' => $request->input('service_department'),
                    'role_id' => Role::APPROVER,
                    'email' => $request->input('email'),
                    'password' => \Hash::make('approver'),
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

            return back()->with('success', 'You have successfully created a new approver.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save a new approver.');
        }
    }

    public function approverDetails(User $approver)
    {
        $suffixes = $this->getSuffixes();
        $branches = $this->getBranches();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_approver',
            compact([
                'approver',
                'suffixes',
                'branches',
            ])
        );
    }

    public function update(Request $request, User $approver)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'bu_department' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => ['required', 'max:80'],
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'editApprover')->withInput();

        try {
            DB::transaction(function () use ($approver, $request) {
                $approver->update([
                    'branch_id' => $request->input('branch'),
                    'department_id' => $request->input('bu_department'),
                    'email' => $request->input('email')
                ]);

                $approver->profile()->update([
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

            return back()->with('success', "You have successfully updated the account for {$approver->profile->getFullName()}.");

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to update the approver. Please try again.');
        }
    }

    public function delete(User $approver)
    {
        try {
            $approver->delete();
            $approver->profile()->delete();

            return back()->with('success', 'Approver successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the approver. Please try again.');
        }
    }

    // For creation of the approver
    public function branchDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }

    // For edit approver
    public function editBrancDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }
}