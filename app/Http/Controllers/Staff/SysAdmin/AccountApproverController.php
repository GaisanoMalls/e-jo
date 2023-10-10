<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Account\StoreApproverRequest;
use App\Http\Requests\SysAdmin\Manage\Account\UpdateApproverRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccountApproverController extends Controller
{
    use Utils, BasicModelQueries;

    public function store(StoreApproverRequest $request)
    {
        // Branches
        if ($request->branches[0] === null) {
            return back()->with('empty_branches', 'Branch field is required.')
                ->withInput();
        }

        $selectedBranches = $this->getSelectedValue($request->branches);
        $existingBranches = Branch::whereIn('id', $selectedBranches)->pluck('id');

        if (count($existingBranches) !== count($selectedBranches)) {
            return back()->with('invalid_branches', 'Invalid branch.')
                ->withInput();
        }

        // BU/Departments
        if ($request->bu_departments[0] === null) {
            return back()->with('empty_bu_departments', 'BU/Department field is required.')
                ->withInput();
        }

        $selectedBUDepartments = $this->getSelectedValue($request->bu_departments);
        $existingBUDepartments = Department::whereIn('id', $selectedBUDepartments)->pluck('id');

        if (count($existingBUDepartments) !== count($selectedBUDepartments)) {
            return back()->with('invalid_bu_departments', 'Invalid branch.')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $existingBranches, $existingBUDepartments) {
                $approver = User::create([
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

                $approver->branches()->attach($existingBranches);
                $approver->buDepartments()->attach($existingBUDepartments);
            });

            return back()->with('success', 'Account successfully created');

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
        $suffixes = $this->querySuffixes();
        $branches = $this->queryBranches();
        $buDepartments = $this->queryBUDepartments();

        return view(
            'layouts.staff.system_admin.manage.accounts.edit.edit_approver',
            compact([
                'approver',
                'suffixes',
                'branches',
                'buDepartments'
            ])
        );
    }

    public function update(UpdateApproverRequest $request, User $approver)
    {
        // Branches
        if ($request->branches[0] === null) {
            return back()->with('empty_branches', 'Branch field is required.')
                ->withInput();
        }

        $selectedBranches = $this->getSelectedValue($request->branches);
        $existingBranches = Branch::whereIn('id', $selectedBranches)->pluck('id');

        if (count($existingBranches) !== count($selectedBranches)) {
            return back()->with('invalid_branches', 'Invalid branch.')
                ->withInput();
        }

        // BU/Departments
        if ($request->bu_departments[0] === null) {
            return back()->with('empty_bu_departments', 'BU/Department field is required.')
                ->withInput();
        }

        $selectedBUDepartments = $this->getSelectedValue($request->bu_departments);
        $existingBUDepartments = Department::whereIn('id', $selectedBUDepartments)->pluck('id');

        if (count($existingBUDepartments) !== count($selectedBUDepartments)) {
            return back()->with('invalid_bu_departments', 'Invalid branch.')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($approver, $request, $existingBranches, $existingBUDepartments) {
                $approver->update([
                    'branch_id' => (int) $request->branch,
                    'department_id' => (int) $request->bu_department,
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

                $approver->branches()->sync($existingBranches);
                $approver->buDepartments()->sync($existingBUDepartments);
            });

            return back()->with('success', "You have successfully updated the account for {$approver->profile->getFullName()}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update the account.');
        }
    }

    public function delete(User $approver)
    {
        try {
            (!is_null($approver->profile->picture))
                ? Storage::delete($approver->profile->picture)
                : '';

            $approver->delete();
            $approver->profile()->delete();

            return back()->with('success', 'Account successfully deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the approver.');
        }
    }

    // For creation and Edit of the approver
    public function branchDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }

}