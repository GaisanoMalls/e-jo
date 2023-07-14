<?php

namespace App\Http\Controllers\Staff\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLevel;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\ServiceDepartment;
use App\Models\ServiceLevelAgreement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HelpTopicsController extends Controller
{
     public function index()
    {
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();
        $levelOfApprovals = ApprovalLevel::orderBy('description', 'asc')->get();
        $approvers = User::where('role_id', Role::APPROVER)->get();
        $slas = ServiceLevelAgreement::orderBy('time_unit', 'asc')->get();
        $helpTopics = HelpTopic::with(['serviceDepartment', 'department', 'sla'])->orderBy('created_at', 'desc')->get();

        return view('layouts.staff.system_admin.manage.help_topics.help_topics_index',
            compact([
                'serviceDepartments',
                'levelOfApprovals',
                'approvers',
                'slas',
                'helpTopics'
            ])
        );
    }

    public function store(Request $request, HelpTopic $helpTopic)
    {
        $validator = Validator::make($request->all(), [
            'service_department' => ['required'],
            'team' => ['required'],
            'sla' => ['required'],
            'name' => ['required', 'unique:help_topics,name'],
            'level_of_approver' => ['nullable'],
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeHelpTopic')->withInput();

        $helpTopic->create([
            'service_department_id' => (int) $request->input('service_department'),
            'team_id' => (int) $request->input('team'),
            'sla_id' => (int) $request->input('sla'),
            'name' => $request->input('name'),
            'level_of_approver' => $request->input('level_of_approver'),
            'slug' => \Str::slug($request->input('name'))
        ]);


        return back()->with('success', 'Help topic is successfully created.');
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
}
