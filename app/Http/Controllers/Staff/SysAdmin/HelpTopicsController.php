<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SysAdmin\Manage\HelpTopic\StoreHelpTopicRequest;
use App\Http\Requests\SysAdmin\Manage\HelpTopic\UpdateHelpTopicRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use App\Models\ServiceDepartment;
use Illuminate\Support\Facades\DB;

class HelpTopicsController extends Controller
{
    use Utils, BasicModelQueries;

    public function index()
    {
        $serviceDepartments = $this->queryServiceDepartments();
        $levelOfApprovals = $this->queryLevelOfApprovals();
        $approvers = $this->queryApprovers();
        $slas = $this->queryServiceLevelAgreements();
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

    public function store(StoreHelpTopicRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $helpTopic = HelpTopic::create([
                    'service_department_id' => $request->service_department,
                    'team_id' => $request->team,
                    'sla_id' => $request->sla,
                    'name' => $request->name,
                    'slug' => \Str::slug($request->name)
                ]);

                $levelOfApproval = (int) $request->level_of_approval;

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

            return back()->with('success', 'Help topic successfully created.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save the help topic');
        }

    }

    public function viewDetails()
    {
        //
    }

    public function editDetails(HelpTopic $helpTopic)
    {
        //
    }

    public function update(UpdateHelpTopicRequest $request, HelpTopic $helpTopic)
    {
        try {
            DB::transaction(function () use ($request, $helpTopic) {
                $helpTopic->update([

                ]);
            });

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to update the help topic.');
        }
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

    public function teams(ServiceDepartment $serviceDepartment)
    {
        return response()->json($serviceDepartment->teams);
    }

    public function loadApprovers()
    {
        $approvers = $this->queryApprovers();
        return response()->json($approvers);
    }
}