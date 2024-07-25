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
use App\Models\SpecialProject;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;


class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;
    public bool $isSpecialProject = false;
    public ?Collection $teams = null;
    public ?string $name = null;
    public ?int $sla = null;
    public ?int $serviceDepartment = null;
    public ?int $team = null;

    // Costing Configuration
    public ?float $amount = null;
    public array $costingApprovers = [];
    public array $finalCostingApprovers = [];
    public array $costingApproversList = [];
    public array $finalCostingApproversList = [];
    public bool $showCostingApproverSelect = false;

    //Approval Configurations
    public array $approvalLevels = [1, 2, 3, 4, 5];
    public array $level1Approvers = [];
    public array $level2Approvers = [];
    public array $level3Approvers = [];
    public array $level4Approvers = [];
    public array $level5Approvers = [];
    public array $selectedApprovers = [];
    public bool $approvalLevelSelected = false;

    public ?array $configurations = [];
    public ?int $buDepartment = null;
    public ?Collection $buDepartments = null;
    public ?int $selectedBuDepartment = null;

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
            'team' => ['required'],
            'selectedBuDepartment' => [empty($this->configurations) ? 'required' : 'nullable'],
            'approvalLevelSelected' => [empty($this->configurations) ? 'accepted' : 'nullable'],
            'teams' => '',
        ];
    }

    public function messages()
    {
        return [
            'selectedBuDepartment.required' => 'BU department field is required',
            'approvalLevelSelected.accepted' => 'Level of approval field is required'
        ];
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadHelpTopics');
        $this->dispatchBrowserEvent('reset-help-topic-form-fields');
    }

    public function saveHelpTopic()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // Save help topic and its configurations
                if (!empty($this->configurations)) {
                    $teamName = $this->team ? Team::find($this->team)->name : '';

                    $helpTopic = HelpTopic::create([
                        'service_department_id' => $this->serviceDepartment,
                        'team_id' => $this->team,
                        'service_level_agreement_id' => $this->sla,
                        'name' => $this->name . ($teamName ? " - {$teamName}" : ''),
                        'slug' => Str::slug($this->name),
                    ]);

                    foreach ($this->configurations as $config) {
                        $helpTopicConfiguration = HelpTopicConfiguration::create([
                            'help_topic_id' => $helpTopic->id,
                            'bu_department_id' => $config['bu_department_id'],
                            'approvers_count' => $config['approvers_count'],
                        ]);

                        foreach ($config['approvers'] as $level => $approversList) {
                            $levelNumber = str_replace('level', '', $level);
                            foreach ($approversList as $approverId) {
                                HelpTopicApprover::create([
                                    'help_topic_configuration_id' => $helpTopicConfiguration->id,
                                    'help_topic_id' => $helpTopic->id,
                                    'level' => $levelNumber,
                                    'user_id' => $approverId,
                                ]);
                            }
                        }
                    }
                }

                if ($this->isSpecialProject) {
                    if (!$this->amount) {
                        $this->addError('amount', 'Amount field is required');
                        return;
                    }

                    $amount = number_format((float) $this->amount, 2, thousands_separator: '');
                    HelpTopicCosting::create([
                        'help_topic_id' => $helpTopic->id,
                        'costing_approvers' => array_map('intval', $this->costingApprovers),
                        'amount' => $amount,
                        'final_costing_approvers' => array_map('intval', $this->finalCostingApprovers),
                    ]);

                    SpecialProject::create([
                        'help_topic_id' => $helpTopic->id,
                        'amount' => $amount,
                    ]);
                }

                $this->actionOnSubmit();
                noty()->addSuccess('Help topic created successfully.');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedIsSpecialProject($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('show-costing-section');
        }
    }

    public function updatedServiceDepartment($value)
    {
        $this->teams = Team::where('service_department_id', $value)->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
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

        if (!$this->selectedBuDepartment) {
            $this->addError('selectedBuDepartment', 'BU department field is required');
        }

        if (!$this->approvalLevelSelected) {
            $this->addError('approvalLevelSelected', 'Level of approval field is required');
        }

        // Check if BU department and level of approval is selected
        if ($this->selectedBuDepartment && $this->approvalLevelSelected) {
            // Get the selected BU Department name
            $buDepartmentName = collect($this->queryBUDepartments())->firstWhere('id', $this->selectedBuDepartment)['name'];
            // Add to the configurations array
            $this->configurations[] = [
                'bu_department_id' => $this->selectedBuDepartment,
                'bu_department_name' => $buDepartmentName,
                'approvers_count' => $approversCount,
                'approvers' => $approvers,
            ];

            $this->clearValidation();
            $this->resetApprovalConfigFields();
        }
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
        $this->selectedApprovers = array_merge(
            (array) $this->level1Approvers,
            (array) $this->level2Approvers,
            (array) $this->level3Approvers,
            (array) $this->level4Approvers,
            (array) $this->level5Approvers
        );

        $filteredApprovers = User::with(['profile', 'roles', 'buDepartments'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', $this->selectedApprovers)
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
