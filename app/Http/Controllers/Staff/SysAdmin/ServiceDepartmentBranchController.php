<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ServiceDepartment;
use App\Models\ServiceDepartmentBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceDepartmentBranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('name', 'asc')->get();
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();
        $serviceDepartmentBranches = ServiceDepartmentBranch::with('branch')->orderBy('created_at', 'desc')->get();

        return view('layouts.staff.system_admin.manage.service_departments.service_department_branch',
            compact([
                'branches',
                'serviceDepartments',
                'serviceDepartmentBranches'
            ])
        );
    }

    public function store(Request $request, ServiceDepartmentBranch $serviceDepartmentBranch, ServiceDepartment $serviceDepartment)
    {
        $validator = Validator::make($request->all(), [
            'bu_department' => ['required'],
            'service_department' => ['required'],
            'branch' => ['required']
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeServiceDepartmentBranch')->withInput();

        $isExists = ServiceDepartmentBranch::where('branch_id', $request['branch'])
                                           ->where('service_department_id', $request['service_department'])
                                           ->exists();

        if ($isExists) return back()->withErrors(['service_department' => 'Service department already assigned to this branch.'], 'storeServiceDepartmentBranch')->withInput();

        $serviceDepartmentBranch->create([
            'branch_id' => $request['branch'],
            'service_department_id' => $request['service_department']
        ]);

        $serviceDepartment->where('id', $request->input('service_department'))
                          ->update(['department_id' => $request->input('bu_department')]);

        return back()->with('success', 'Service department successfully assinged to a branch.');
    }

    public function delete(ServiceDepartmentBranch $serviceDepartmentBranch)
    {
        try {
            $serviceDepartmentBranch->delete();
            return back()->with('success', 'Service department with branch successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Service department with branch cannot be deleted.');
        }
    }

    public function branchDepartments(Branch $branch)
    {
        return response()->json($branch->departments);
    }
}
