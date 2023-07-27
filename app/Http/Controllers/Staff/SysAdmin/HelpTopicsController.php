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
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            'name' => ['required', 'unique:help_topics,name'],
            'level_of_approver' => ['nullable'],
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeHelpTopic')->withInput();

        $helpTopic = HelpTopic::create([
            'service_department_id' => (int) $request->input('service_department'),
            'team_id' => (int) $request->input('team'),
            'sla_id' => (int) $request->input('sla'),
            'name' => $request->input('name'),
            'level_of_approver' => $request->input('level_of_approver'),
            'slug' => \Str::slug($request->input('name'))
        ]);

        $approvers = (array) $request->input('approvers');

        // Group the selected approvers by their respective levels
        $groupedApprovers = collect($approvers)->groupBy(function ($approver, $key) {
            return explode('[', explode(']', $key)[0]); // Extract the level number from the input name
        })->toArray();

        // $groupedApprovers is now an array with keys as level numbers and values as arrays of selected approvers for each level
        // For example, if level_of_approval is 2 and you selected 3 approvers for level 1 and 2 approvers for level 2,
        // $groupedApprovers will look like this:
        // [
        //     1 => ['approver1', 'approver2', 'approver3'],
        //     2 => ['approver4', 'approver5'],
        // ]

        // Now you can process and store the data as needed for each level
        foreach ($groupedApprovers as $level => $selectedApprovers) {
            dd($level, $selectedApprovers);
            // $level will be the level number (e.g., 1, 2, etc.)
            // $selectedApprovers will be an array of selected approver IDs for that level

            // You can use these values to store the data in the database or perform any other processing as needed
            // For example:
            foreach ($selectedApprovers as $approver) {
                dd($approver);
                // Store the approver ID along with the level in the database
                // You can use Eloquent models and relationships to do this efficiently
                // For example:
                ApprovalLevel::create([
                    'value' => $approver,
                    'description' => "Approver for Level $level",
                ]);
            }
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