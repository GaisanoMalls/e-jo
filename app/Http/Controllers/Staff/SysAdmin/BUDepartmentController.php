<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\BUDepartment\StoreBUDepartmentRequest;
use App\Http\Traits\Utils;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BUDepartmentController extends Controller
{
    use Utils;

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

    public function update(Request $request, Department $buDepartment)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'name' => ['required']
        ]);

        if ($validator->fails()) {
            $request->session()->put('buDepartmentId', $buDepartment->id); // set a session containing the pk of department to show modal based on the selected record.
            return back()->withErrors($validator, 'editBUDepartment')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $buDepartment) {
                $buDepartment->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name)
                ]);

                $buDepartment->branches()->sync($request->branch);
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