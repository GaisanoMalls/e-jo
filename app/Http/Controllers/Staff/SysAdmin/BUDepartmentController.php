<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BUDepartmentController extends Controller
{
    public function index()
    {
        $buDepartments = Department::with('branches')->orderBy('created_at', 'desc')->get();
        return view(
            'layouts.staff.system_admin.manage.bu_departments.bu_department_index',
            compact([
                'buDepartments'
            ])
        );
    }

    public function store(Request $request, Department $buDepartment)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:departments,name',
                'regex:/^[a-zA-Z\s]+$/u',
                function ($attribute, $value, $fail) {
                    if (preg_match('/[\'^£$%&*}{@#~?><>,|=_+¬-]/', $value)) {
                        $fail('The name cannot contain special characters.');
                    }
                },
            ]
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeBUDepartment')->withInput();

        $buDepartment->create([
            'name' => $request->input('name'),
            'slug' => \Str::slug($request->input('name'))
        ]);

        return back()->with('success', 'Bu/Department successfully added.');
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