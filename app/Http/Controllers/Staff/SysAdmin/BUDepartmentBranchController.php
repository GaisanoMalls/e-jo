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

        return view('layouts.staff.system_admin.manage.bu_departments.bu_department_branch',
            compact([
                'buDepartmentBranches',
                'buDepartments',
                'branches'
            ])
        );
    }

    public function store(Request $request, DepartmentBranch $departmentBranch)
    {
        $validator = Validator::make($request->all(), [
            'bu_department' => ['required'],
            'branch' => ['required']
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeBUDepartmentBranch')->withInput();

        $isExists = DepartmentBranch::where('department_id', $request['bu_department'])
                                    ->where('branch_id', $request['branch'])
                                    ->exists();

        if ($isExists) return back()->withErrors(['bu_department' => 'BU/department already assigned to this branch.'], 'storeBUDepartmentBranch')->withInput();

        $departmentBranch->create([
            'department_id' => $request->input('bu_department'),
            'branch_id' => $request->input('branch')
        ]);

        return back()->with('success', 'BU/department successfully assigned to a branch.');
    }
}
