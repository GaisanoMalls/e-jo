<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\HelpTopicApprover;
use App\Models\HelpTopicConfiguration;
use App\Models\HelpTopicCosting;
use App\Models\Role;
use App\Models\ServiceDepartmentChildren;
use App\Models\SpecialProject;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Log;


class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;
    public $isSpecialProject = false;
    public $teams = [];
    public $serviceDepartmentChildren = [];
    public $selectedServiceDepartmentChildrenId;
    public $selectedServiceDepartmentChildrenName;
    public $name;
    public $sla;
    public $serviceDepartment;
    public $team;

    // Costing Configuration
    public $amount;
    public $costingApprovers = [];
    public $finalCostingApprovers = [];

    public $costingApproversList = [];
    public $finalCostingApproversList = [];
    public $showCostingApproverSelect = false;


    //Approval Configurations
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



    public function mount()
    {
        $this->buDepartments = $this->queryBUDepartments();
        $this->fetchCostingApprovers();
    }

    public function rules()
    {
        return [
            'name' => ['required', 'unique:help_topics,name'],
            'sla' => ['required'],
            'serviceDepartment' => ['required'],
            'team' => ['nullable'],
            'amount' => ['numeric', $this->isSpecialProject ? 'required' : 'nullable'],
            'teams' => '',
        ];
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadHelpTopics');
        $this->hideSpecialProjectContainer();
    }

    public function saveHelpTopic()
    {
        // Validate form inputs
        $this->validate();

        // Check if team is required for special projects
        if ($this->isSpecialProject && !$this->team) {
            session()->flash('team_error', 'Team is required for special projects');
            return;
        }

        try {
            Log::info('Starting transaction to save HelpTopic.');

            DB::transaction(function () {
                $teamName = $this->team ? Team::find($this->team)->name : '';
                // Create HelpTopic
                $helpTopic = HelpTopic::create([
                    'service_department_id' => $this->serviceDepartment,
                    'service_dept_child_id' => null, // No sub-service department check
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name . ($teamName ? " - {$teamName}" : ''),
                    'slug' => Str::slug($this->name),
                ]);

                Log::info('HelpTopic created successfully.', ['help_topic_id' => $helpTopic->id]);

                // Save help topic configurations
                foreach ($this->configurations as $config) {
                    // Create the configuration record
                    $helpTopicConfiguration = HelpTopicConfiguration::create([
                        'help_topic_id' => $helpTopic->id,
                        'bu_department_id' => $config['bu_department_id'],
                        'bu_department_name' => $config['bu_department_name'],
                        'approvers_count' => $config['approvers_count'],
                    ]);

                    // Create the approver records
                    foreach ($config['approvers'] as $level => $approversList) {
                        $levelNumber = str_replace('level', '', $level);
                        foreach ($approversList as $userId) {
                            HelpTopicApprover::create([
                                'help_topic_configuration_id' => $helpTopicConfiguration->id,
                                'help_topic_id' => $helpTopic->id,
                                'level' => $levelNumber,
                                'user_id' => $userId,
                            ]);
                        }
                    }
                }

                // Save costing data
                HelpTopicCosting::create([
                    'help_topic_id' => $helpTopic->id,
                    'costing_approvers' => $this->costingApprovers,
                    'amount' => $this->amount,
                    'final_costing_approvers' => $this->finalCostingApprovers,
                ]);

                // Create SpecialProject if it's a special project
                if ($this->isSpecialProject) {
                    SpecialProject::create([
                        'help_topic_id' => $helpTopic->id,
                        'amount' => $this->amount,
                    ]);

                    Log::info('SpecialProject created successfully.', ['help_topic_id' => $helpTopic->id]);
                }
            });

            // Action on successful submission
            $this->actionOnSubmit();
            noty()->addSuccess('A new help topic has been created.');
            Log::info('Help topic submission successful.');
        } catch (Exception $e) {
            // Log the error
            AppErrorLog::getError($e->getMessage());
            Log::error('Error occurred while saving help topic.', ['exception' => $e->getMessage()]);
        }
    }


    public function updatedServiceDepartment($value)
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $value))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
    }


    public function showSpecialProjectContainer()
    {
        $this->dispatchBrowserEvent('show-special-project-container');
    }

    public function hideSpecialProjectContainer()
    {
        $this->name = null;
        $this->amount = null;
        $this->dispatchBrowserEvent('hide-special-project-container');
    }

    public function updatedIsSpecialProject($value)
    {
        ($value)
            ? $this->showSpecialProjectContainer()
            : $this->hideSpecialProjectContainer();
        $this->resetValidation();
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


    public function updatedApprovalLevelSelected()
    {
        $this->getFilteredApprovers(1);
    }

    public function updatedLevel1Approvers()
    {
        $this->getFilteredApprovers(2);
    }

    public function updatedLevel2Approvers()
    {
        $this->getFilteredApprovers(3);
    }

    public function updatedLevel3Approvers()
    {
        $this->getFilteredApprovers(4);
    }

    public function updatedLevel4Approvers()
    {
        $this->getFilteredApprovers(5);
    }
    public function updatedBuDepartment($value)
    {
        $this->getFilteredApprovers($value);
    }

    public function getFilteredApprovers($level)
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

        $this->dispatchBrowserEvent('load-approvers', ['approvers' => $filteredApprovers, 'level' => $level]);
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

    public function render()
    {
        return view('livewire.staff.help-topic.create-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'buDepartments' => $this->queryBUDepartments(),
            'configurations' => $this->configurations,
            'costingApproversList' => $this->costingApproversList,
            'finalCostingApproversList' => $this->finalCostingApproversList,
        ]);
    }
}
