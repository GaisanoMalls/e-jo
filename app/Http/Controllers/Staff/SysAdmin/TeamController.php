<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\SlugGenerator;
use App\Models\Department;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    use SlugGenerator;

    public function index()
    {
        $serviceDepartments = ServiceDepartment::orderby('name', 'asc')->get();
        $teams = Team::with('serviceDepartment')
            ->with([
                'users' => function ($query) {
                    $query->whereHas('role', function ($roleQuery) {
                        $roleQuery->where('id', Role::AGENT);
                    });
                }
            ])->orderBy('created_at', 'desc')->get();

        return view(
            'layouts.staff.system_admin.manage.teams.teams_index',
            compact([
                'serviceDepartments',
                'teams'
            ])
        );
    }

    public function store(Request $request, Team $team)
    {
        $validator = Validator::make($request->all(), [
            'service_department' => ['required'],
            'name' => [
                'required',
                'unique:teams,name',
                function ($attribute, $value, $fail) {
                    $departments = Department::pluck('name')->toArray();

                    if (in_array($value, $departments)) {
                        $fail("The team name cannot be the same as any of the department names.");
                    }
                }
            ]
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTeam')->withInput();

        $team->create([
            'service_department_id' => (int) $request->input('service_department'),
            'name' => $request['name'],
            'slug' => $this->slugify($request->input('name'))
        ]);

        return back()->with('success', 'Team successfully created.');
    }

    public function delete(Team $team)
    {
        try {
            $team->delete();
            return back()->with('success', 'Team successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Team cannot be deleted.');
        }
    }

}