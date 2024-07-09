<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use App\Models\HelpTopicApprover;
use App\Models\HelpTopicConfiguration;
use App\Models\HelpTopicCosting;
use App\Models\Role;
use App\Models\SpecialProject;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;


class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public $isSpecialProject = false;
    public $teams = [];
    public $name;
    public $sla;
    public $serviceDepartment;
    public $team;
    public $amount;

    // Costing Configuration
    public $costingApprovers = [];
    public $finalCostingApprovers = [];
    public $costingApproversList = [];
    public $finalCostingApproversList = [];
    public $showCostingApproverSelect = false;

    // Approval Configurations
    public $approvalLevels = [1, 2, 3, 4, 5];
    public $levelApprovers = null;
    public $level1Approvers = [];
    public $level2Approvers = [];
    public $level3Approvers = [];
    public $level4Approvers = [];
    public $level5Approvers = [];
    public $approvalLevelSelected = false;

    public $buDepartment;
    public $buDepartments;
    public $configurations = [];
    public $selectedBuDepartment;
    public $selectedApproversCount = 0;

    public function mount(HelpTopic $helpTopic)
    {
        $this->helpTopic = $helpTopic;
        $this->name = preg_replace('/ - [^-]+$/', '', $helpTopic->name);
        $this->sla = $helpTopic->service_level_agreement_id;
        $this->serviceDepartment = $helpTopic->service_department_id;
        $this->team = $helpTopic->team_id;
        $this->amount = $helpTopic->specialProject ? $helpTopic->specialProject->amount : null;
        $this->costingApprovers = is_array($helpTopic->costing?->costing_approvers) ? $helpTopic->costing->costing_approvers : json_decode($helpTopic->costing?->costing_approvers, true);
        $this->finalCostingApprovers = is_array($helpTopic->costing?->final_costing_approvers) ? $helpTopic->costing->final_costing_approvers : json_decode($helpTopic->costing?->final_costing_approvers, true);
        $this->teams = Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $helpTopic->service_department_id))->get(['id', 'name']);
        $this->isSpecialProject = $helpTopic->specialProject ? true : false;
        $this->buDepartments = $this->queryBUDepartments();
        $this->fetchCostingApprovers();
        $this->loadConfigurations();
    }

    public function rules()
    {
        return [
            'name' => "required|unique:help_topics,name,{$this->helpTopic->id}",
            'sla' => 'required',
            'serviceDepartment' => 'required',
            'team' => 'nullable|required_if:isSpecialProject,true',
            'amount' => $this->isSpecialProject ? 'required|numeric' : 'nullable|numeric',
        ];
    }

    public function updateHelpTopic()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $teamName = $this->team ? Team::find($this->team)->name : '';

                $this->helpTopic->update([
                    'service_department_id' => $this->serviceDepartment,
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name . ($teamName ? " - {$teamName}" : ''),
                    'slug' => Str::slug($this->name),
                ]);

                if ($this->isSpecialProject) {
                    SpecialProject::updateOrCreate(
                        ['help_topic_id' => $this->helpTopic->id],
                        ['amount' => $this->amount]
                    );
                } else {
                    SpecialProject::where('help_topic_id', $this->helpTopic->id)->delete();
                }

                HelpTopicCosting::updateOrCreate(
                    ['help_topic_id' => $this->helpTopic->id],
                    [
                        'costing_approvers' => $this->costingApprovers,
                        'amount' => $this->amount,
                        'final_costing_approvers' => $this->finalCostingApprovers,
                    ]
                );

                // Delete existing configurations and re-create them
                $this->helpTopic->configurations()->delete();

                foreach ($this->configurations as $config) {
                    $helpTopicConfiguration = HelpTopicConfiguration::create([
                        'help_topic_id' => $this->helpTopic->id,
                        'bu_department_id' => $config['bu_department_id'],
                        'bu_department_name' => $config['bu_department_name'],
                        'approvers_count' => $config['approvers_count'],
                    ]);

                    foreach ($config['approvers'] as $level => $approversList) {
                        $levelNumber = str_replace('level', '', $level);
                        foreach ($approversList as $userId) {
                            HelpTopicApprover::create([
                                'help_topic_configuration_id' => $helpTopicConfiguration->id,
                                'help_topic_id' => $this->helpTopic->id,
                                'level' => $levelNumber,
                                'user_id' => $userId,
                            ]);
                        }
                    }
                }
            });

            noty()->addSuccess('Help topic successfully updated.');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }


    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->serviceDepartment))->get(['id', 'name']);
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
    }

    public function fetchCostingApprovers()
    {
        $users = User::with(['profile', 'roles'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->get();

        $this->costingApproversList = $users->map(function ($user) {
            return [
                'label' => $user->profile->first_name . ' ' . $user->profile->last_name,
                'value' => $user->id,
                'description' => $user->roles->pluck('name')->join(', ')
            ];
        })->toArray();

        $this->finalCostingApproversList = $this->costingApproversList;
    }

    public function loadConfigurations()
    {
        $this->configurations = $this->helpTopic->configurations->map(function ($config) {
            return [
                'bu_department_id' => $config->bu_department_id,
                'bu_department_name' => $config->bu_department_name,
                'approvers_count' => $config->approvers_count,
                'approvers' => $config->approvers->groupBy('level')->map(function ($approvers) {
                    return $approvers->pluck('user_id');
                })->toArray(),
            ];
        })->toArray();
    }

    public function getFilteredApprovers2($level)
    {
        $selectedApprovers = array_merge(
            (array) $this->level1Approvers,
            (array) $this->level2Approvers,
            (array) $this->level3Approvers,
            (array) $this->level4Approvers,
            (array) $this->level5Approvers
        );

        $filteredApprovers = User::with(['profile', 'roles'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', $selectedApprovers)
            ->orderByDesc('created_at')
            ->get();

        $this->dispatchBrowserEvent('load-approvers2', ['approvers' => $filteredApprovers, 'level' => $level]);
    }

    public function saveConfiguration()
    {
        $approvers = [
            'level1' => $this->level1Approvers,
            'level2' => $this->level2Approvers,
            'level3' => $this->level3Approvers,
            'level4' => $this->level4Approvers,
            'level5' => $this->level5Approvers,
        ];

        $approversCount = array_sum(array_map('count', $approvers));

        // Get the selected BU Department name
        $buDepartmentName = collect($this->buDepartments)->firstWhere('id', $this->selectedBuDepartment)['name'];

        // Add to the configurations array
        $this->configurations[] = [
            'bu_department_id' => $this->selectedBuDepartment,
            'bu_department_name' => $buDepartmentName,
            'approvers_count' => $approversCount,
            'approvers' => $approvers,
        ];

        $this->resetApprovalConfigFields();
    }
    private function resetApprovalConfigFields()
    {
        $this->selectedBuDepartment = null;
        $this->approvalLevelSelected = false;
        $this->level1Approvers = [];
        $this->level2Approvers = [];
        $this->level3Approvers = [];
        $this->level4Approvers = [];
        $this->level5Approvers = [];
        $this->dispatchBrowserEvent('reset-select-fields');
    }
    public function removeConfiguration($index)
    {
        array_splice($this->configurations, $index, 1);
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
