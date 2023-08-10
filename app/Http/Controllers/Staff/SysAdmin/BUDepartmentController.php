<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\MultiSelect;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BUDepartmentController extends Controller
{
    use MultiSelect;

    public function index()
    {
        $buDepartments = Department::with('branches')->orderBy('created_at', 'desc')->get();
        $branches = Branch::orderBy('name', 'asc')->get();
        return view(
            'layouts.staff.system_admin.manage.bu_departments.bu_department_index',
            compact([
                'buDepartments',
                'branches'
            ])
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branches' => ['array'],
            'name' => [
                'required',
                'unique:departments,name',
            ]
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'storeBUDepartment')
                ->withInput();
        }

        if ($request->input('branches')[0] === null) {
            return back()->with('empty_branch', 'Branch is required.')
                ->withInput();
        }

        $selectedBranches = $this->getSelectedValue($request->input('branches'));

        $existingBranches = Branch::whereIn('id', $selectedBranches)->pluck('id');
        if (count($existingBranches) !== count($selectedBranches)) {
            return back()->with('invalid_branch', 'Invalid branch selected.')
                ->withInput();
        }

        DB::transaction(function () use ($request, $existingBranches) {
            $department = Department::create([
                'name' => $request->input('name'),
                'slug' => \Str::slug($request->input('name'))
            ]);

            $department->branches()->attach($existingBranches);
        });

        return back()->with('success', 'BU/Department successfully added.');
    }

    public function update(Request $request, Department $buDepartment)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'name' => ['required']
        ]);

        if ($validator->fails()) {
            $request->session()->put('buDepartmentId', $buDepartment->id); // set a session containing the pk of department to show modal based on the selected record.
            return back()->withErrors($validator, 'editBUDepartment')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $buDepartment) {
                $buDepartment->update([
                    'name' => $request->input('name'),
                    'slug' => Str::slug($request->input('name'))
                ]);

                $buDepartment->branches()->sync($request->input('branch', []));
            });

            $request->session()->forget('buDepartmentId'); // remove the buDepartmentId in the session when form is successful or no errors.
            return back()->with('success', 'BU/Department successfully updated.');

        } catch (\Exception $e) {
            $request->session()->put('buDepartmentId', $buDepartment->id); // set a session containing the pk of department to show modal based on the selected record.
            return back()->with('duplicate_name_error', "BU/Department name {$request->name} already exists.");
        }
    }

    public function delete(Department $buDepartment)
    {
        try {
            $buDepartment->delete();
            return back()->with('success', 'BU/Department successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'BU/Department cannot be deleted.');
        }
    }
}