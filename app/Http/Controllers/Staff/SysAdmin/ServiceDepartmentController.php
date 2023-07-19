<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\DepartmentRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\ServiceDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceDepartmentController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['auth', Role::systemAdmin()]);
    // }

    public function index()
    {
        $serviceDepartments = ServiceDepartment::orderBy('created_at', 'desc')->get();
        $buDepartments = Department::orderBy('name', 'desc')->get();

        return view('layouts.staff.system_admin.manage.service_departments.service_department_index',
            compact([
                'serviceDepartments',
                'buDepartments',
            ])
        );
    }

    public function store(Request $request, ServiceDepartment $serviceDepartment)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:service_departments,name',
                'regex:/^[a-zA-Z\s]+$/u',
                function ($attribute, $value, $fail) {
                    if (preg_match('/[\'^£$%&*}{@#~?><>,|=_+¬-]/', $value)) {
                        $fail('The name cannot contain special characters.');
                    }
                },
            ]
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeServiceDepartment')->withInput();

        $serviceDepartment->create([
            'department_id' => $request->input('bu_department'),
            'name' => $request->input('name'),
            'slug' => Str::slug($request['name'])
        ]);

        return back()->with('success', 'Department successfully added.');
    }

    public function delete(ServiceDepartment $serviceDepartment)
    {
        try {
            $serviceDepartment->delete();
            return back()->with('success', 'Service department successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Service department cannot be deleted.');
        }
    }
}