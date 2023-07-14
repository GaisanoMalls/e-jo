<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamBranch;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\ServiceDepartment;
use Illuminate\Support\Facades\Validator;

class TeamBranchController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('name', 'asc')->get();
        $branches = Branch::orderBy('name', 'asc')->get();
        $teamBranch = TeamBranch::with(['team', 'branch'])->orderBy('created_at', 'desc')->get();

        return view('layouts.staff.system_admin.manage.teams.team_branch',
            compact([
                'teams',
                'teamBranch',
                'branches'
            ])
        );
    }

   public function store(Request $request, TeamBranch $tdb)
    {
        $validator = Validator::make($request->all(), [
            'team' => ['required'],
            'branch' => ['required']
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeTeamBranch')->withInput();

        $tdb->create([
            'team_id' => (int) $request->input('team'),
            'branch_id' => (int) $request->input('branch')
        ]);

        return back()->with('success', 'Team successfully assigned to branch.');
    }

    public function delete(TeamBranch $tdb)
    {
        try {
            $tdb->delete();
            return back()->with('success', 'Team successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Team setup cannot be deleted.');
        }
    }

    public function serviceDepartment(Team $team)
    {
        return response()->json($team->serviceDepartment);
    }

    public function branches(ServiceDepartment $serviceDepartment)
    {
        return response()->json($serviceDepartment->branches);
    }
}
