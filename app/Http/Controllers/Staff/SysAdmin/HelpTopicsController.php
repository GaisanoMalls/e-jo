<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;

class HelpTopicsController extends Controller
{
    use Utils, BasicModelQueries;

    public function index()
    {
        $serviceDepartments = $this->queryServiceDepartments();
        $approvers = $this->queryApprovers();
        $slas = $this->queryServiceLevelAgreements();

        $helpTopics = HelpTopic::with(['serviceDepartment', 'department', 'sla'])->orderByDesc('created_at')->get();

        return view(
            'layouts.staff.system_admin.manage.help_topics.help_topics_index',
            compact([
                'serviceDepartments',
                'approvers',
                'slas',
                'helpTopics',
            ])
        );
    }

    public function editDetails(HelpTopic $helpTopic)
    {
        $approvers = $this->queryApprovers();
        $serviceDepartments = $this->queryServiceDepartments();
        $serviceLevelAgreements = $this->queryServiceLevelAgreements();

        return view(
            'layouts.staff.system_admin.manage.help_topics.edit_help_topic',
            compact([
                'helpTopic',
                'serviceDepartments',
                'serviceLevelAgreements',
                'approvers',
            ])
        );
    }
}