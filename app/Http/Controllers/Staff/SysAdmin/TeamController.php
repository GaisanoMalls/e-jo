<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\MultiSelect;
use App\Http\Traits\SlugGenerator;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    use SlugGenerator, MultiSelect;

    public function index()
    {
        $serviceDepartments = ServiceDepartment::orderby('name', 'asc')->get();
        $branches = Branch::orderBy('name', 'asc')->get();
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
                'branches',
                'teams'
            ])
        );
    }

    public function store(Request $request)
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

        if ($validator->fails()) {
            return back()->withErrors($validator, 'storeTeam')
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
            $team = Team::create([
                'service_department_id' => $request->input('service_department'),
                'name' => $request['name'],
                'slug' => $this->slugify($request->input('name'))
            ]);

            $team->branches()->attach($existingBranches);
        });

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