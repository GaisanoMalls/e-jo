<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\MultiSelect;
use App\Models\ApprovalLevel;
use App\Models\HelpTopic;
use App\Models\Level;
use App\Models\LevelApprover;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HelpTopicsController extends Controller
{
    use MultiSelect;

    public function index()
    {
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();
        $levelOfApprovals = ApprovalLevel::orderBy('description', 'asc')->get();
        $approvers = User::where('role_id', Role::APPROVER)->get();
        $slas = ServiceLevelAgreement::orderBy('time_unit', 'asc')->get();
        $helpTopics = HelpTopic::with(['serviceDepartment', 'department', 'sla'])->orderBy('created_at', 'desc')->get();

        return view(
            'layouts.staff.system_admin.manage.help_topics.help_topics_index',
            compact([
                'serviceDepartments',
                'levelOfApprovals',
                'approvers',
                'slas',
                'helpTopics'
            ])
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_department' => ['required'],
            'team' => ['required'],
            'sla' => ['required'],
            'name' => ['required'],
            'level_of_approval' => ['required'],
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeHelpTopic')->withInput();

        try {
            DB::transaction(function () use ($request) {
                $helpTopic = HelpTopic::create([
                    'service_department_id' => $request->input('service_department'),
                    'team_id' => $request->input('team'),
                    'sla_id' => $request->input('sla'),
                    'name' => $request->input('name'),
                    'slug' => \Str::slug($request->input('name'))
                ]);

                $levelOfApproval = (int) $request->input('level_of_approval');

                for ($level = 1; $level <= $levelOfApproval; $level++) {
                    $helpTopic->levels()->attach($level);
                    $approvers = $this->getSelectedValue($request->input("approvers{$level}"));

                    foreach ($approvers as $approver) {
                        LevelApprover::create([
                            'level_id' => $level,
                            'user_id' => $approver
                        ]);
                    }
                }

            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save the help topic');
        }

        return back()->with('success', 'Help topic successfully created.');
    }

    public function delete(HelpTopic $helpTopic)
    {
        try {
            $helpTopic->delete();
            return back()->with('success', 'Help topic successfully deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete the help topic');
        }
    }

    public function getLevelApprovers()
    {
        $sla = User::approvers();
        return response()->json($sla);
    }

    public function teams(ServiceDepartment $serviceDepartment)
    {
        return response()->json($serviceDepartment->teams);
    }

    public function loadApprovers()
    {
        $approvers = User::with('profile')->where('role_id', Role::APPROVER)->get();
        return response()->json($approvers);
    }
}