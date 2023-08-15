<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\Team\StoreTeamRequest;
use App\Http\Traits\MultiSelect;
use App\Http\Traits\SlugGenerator;
use App\Models\Branch;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function store(StoreTeamRequest $request)
    {
        if ($request->branches[0] === null) {
            return back()->with('empty_branch', 'Branch is required.')
                ->withInput();
        }

        $selectedBranches = $this->getSelectedValue($request->branches);
        $existingBranches = Branch::whereIn('id', $selectedBranches)->pluck('id');

        if (count($existingBranches) !== count($selectedBranches)) {
            return back()->with('invalid_branch', 'Invalid branch selected.')
                ->withInput();
        }

        DB::transaction(function () use ($request, $existingBranches) {
            $team = Team::create([
                'service_department_id' => $request->service_department,
                'name' => $request->name,
                'slug' => $this->slugify($request->name)
            ]);

            $team->branches()->attach($existingBranches);
        });

        return back()->with('success', 'Team successfully created.');
    }

    public function update(Request $request, Team $team)
    {
        $validator = Validator::make($request->all(), [
            'service_department' => ['required'],
            'name' => ['required'],
            'branch' => ['required'],
        ]);

        if ($validator->fails()) {
            $request->session()->put('teamId', $team->id); // set a session containing the pk of department to show modal based on the selected record.
            return back()->withErrors($validator, 'editTeam')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $team) {
                $team->update([
                    'service_department_id' => $request->service_department,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name)
                ]);

                $team->branches()->sync($request->branch);
            });

            $request->session()->forget('teamId'); // remove the buDepartmentId in the session when form is successful or no errors.
            return back()->with('success', 'Team successfully updated.');

        } catch (\Exception $e) {
            $request->session()->put('teamId', $team->id); // set a session containing the pk of department to show modal based on the selected record.
            return back()->with('duplicate_name_error', "Team name {$request->name} already exists.");
        }
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