<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\DepartmentBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BUDepartmentBranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name', 'asc')->get();
        $buDepartments = Department::orderby('name', 'asc')->get();
        $buDepartmentBranches = DepartmentBranch::with('branch')->orderBy('created_at', 'desc')->get();

        return view(
            'layouts.staff.system_admin.manage.bu_departments.bu_department_branch',
            compact([
                'buDepartmentBranches',
                'buDepartments',
                'branches'
            ])
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bu_department' => ['required'],
            'branches' => ['required']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeBUDepartmentBranch')->withInput();

        $isExists = DepartmentBranch::where('department_id', $request->input('bu_department'))
            ->where('branch_id', $request->input('branch'))
            ->exists();

        if ($isExists) {
            return back()
                ->withErrors(['bu_department' => 'BU/department already assigned to this branch.'], 'storeBUDepartmentBranch')
                ->withInput();
        }

        $department = Department::find($request->bu_department);
        $selectedBranches = $request->input('branches');
        $department->branches()->attach($selectedBranches);

        return back()->with('success', 'BU/department successfully assigned to a branch.');
    }

    public function delete(DepartmentBranch $departmentBranch)
    {
        try {
            $departmentBranch->delete();
            return back()->with('success', 'BU department with branch has been successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'BU department with branch cannot be deleted.');
        }
    }
}