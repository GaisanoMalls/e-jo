<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\LevelApprover;

class HelpTopicsController extends Controller
{
    use Utils, BasicModelQueries;

    public function index()
    {
        $serviceDepartments = $this->queryServiceDepartments();
        $levelOfApprovals = $this->queryLevelOfApprovals();
        $approvers = $this->queryApprovers();
        $slas = $this->queryServiceLevelAgreements();

        $helpTopics = HelpTopic::with(['serviceDepartment', 'department', 'sla'])->orderByDesc('created_at')->get();

        return view('layouts.staff.system_admin.manage.help_topics.help_topics_index',
            compact([
                'serviceDepartments',
                'levelOfApprovals',
                'approvers',
                'slas',
                'helpTopics',
            ])
        );
    }

    public function editDetails(HelpTopic $helpTopic)
    {
        $approvers = $this->queryApprovers();
        $levelOfApprovals = $this->queryLevelOfApprovals();
        $serviceDepartments = $this->queryServiceDepartments();
        $serviceLevelAgreements = $this->queryServiceLevelAgreements();

        $levelApprovers = LevelApprover::where('help_topic_id', $helpTopic->id)->get();

        return view(
            'layouts.staff.system_admin.manage.help_topics.edit_help_topic',
            compact([
                'helpTopic',
                'levelOfApprovals',
                'serviceDepartments',
                'serviceLevelAgreements',
                'levelApprovers',
                'approvers',
            ])
        );
    }
}