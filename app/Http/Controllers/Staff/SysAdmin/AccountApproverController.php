<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreApproverRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateApproverRequest;
use App\Http\Traits\MultiSelect;
use App\Http\Traits\SlugGenerator;
use App\Http\Traits\UserDetails;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountApproverController extends Controller
{
    use SlugGenerator, UserDetails, MultiSelect;

    public function store(StoreApproverRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $approver = User::create([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'role_id' => Role::APPROVER,
                    'email' => $request->email,
                    'password' => \Hash::make('approver'),
                ]);

                Profile::create([
                    'user_id' => $approver->id,
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

            return back()->with('success', 'You have successfully created a new approver.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to save a new approver.');
        }
    }

    public function viewDetails(User $approver)
    {
        return view(
            'layouts.staff.system_admin.manage.accounts.roles.details.approver_details',
            compact('approver')
        );
    }

    public function editDetails(User $approver)
    {
        $suffixes = $this->suffixes();
        $branches = $this->branches();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_approver',
            compact([
                'approver',
                'suffixes',
                'branches',
            ])
        );
    }

    public function update(UpdateApproverRequest $request, User $approver)
    {
        try {
            DB::transaction(function () use ($approver, $request) {
                $approver->update([
                    'branch_id' => $request->branch,
                    'department_id' => $request->bu_department,
                    'email' => $request->email
                ]);

                $approver->profile()->update([
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

            return back()->with('success', "You have successfully updated the account for {$approver->profile->getFullName()}.");

        } catch (\Exception $e) {
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

    // For creation and Edit of the approver
    public function branchDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }

}