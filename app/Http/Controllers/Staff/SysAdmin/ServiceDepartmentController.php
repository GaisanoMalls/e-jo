<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ServiceDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceDepartmentController extends Controller
{
    public function index()
    {
        $serviceDepartments = ServiceDepartment::orderBy('created_at', 'desc')->get();
        $buDepartments = Department::orderBy('name', 'desc')->get();

        return view(
            'layouts.staff.system_admin.manage.service_departments.service_department_index',
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
            ]
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeServiceDepartment')->withInput();

        $serviceDepartment->create([
            'department_id' => $request->input('bu_department'),
            'name' => $request->input('name'),
            'slug' => Str::slug($request['name'])
        ]);

        return back()->with('success', 'Department successfully added.');
    }

    public function update(Request $request, ServiceDepartment $serviceDepartment)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:service_departments,name']
        ]);

        if ($validator->fails()) {
            $request->session()->put('serviceDepartmentId', $serviceDepartment->id); // set a session containing the pk of service department to show modal based on the selected record.
            return back()->withErrors($validator, 'editServiceDepartment')
                ->withInput();
        }

        $serviceDepartment->update([
            'name' => $request->input('name')
        ]);

        $request->session()->forget('serviceDepartmentId'); // remove the serviceDepartmentId in the session when form is successful or no errors.
        return back()->with('success', 'Service department successfully updated.');
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