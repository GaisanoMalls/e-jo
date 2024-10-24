<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Department;
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


class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public Collection $teams;
    public string $name;
    public int $sla;
    public int $serviceDepartment;
    public int $team;
    public ?float $amount = null;
    public bool $isSpecialProject = false;

    // Costing Configuration
    public $costingApprovers = [];
    public $finalCostingApprovers = [];
    public $costingApproversList = [];
    public $finalCostingApproversList = [];
    public bool $showCostingApproverSelect = false;

    // Approval Configurations
    public array $approvalLevels = [1, 2, 3, 4, 5];
    /**
     * Level 1 to 5 approvers
     * @var array<int>
     */
    public array $level1Approvers = [];
    public array $level2Approvers = [];
    public array $level3Approvers = [];
    public array $level4Approvers = [];
    public array $level5Approvers = [];

    public ?int $levelOfApproval = null;

    public Collection $buDepartments;
    public int $selectedApproversCount = 0;
    public ?int $selectedBuDepartment = null;
    public ?Collection $currentConfigurations = null;
    public array $addedConfigurations = [];

    // Edit help topic configuration
    public ?Department $currentConfigBuDepartment = null;
    public ?HelpTopicConfiguration $currentHelpTopicConfiguration = null;
    public array $selectedApprovers = [];

    // Delete selected helptopic configuration
    public ?int $deleteSelectedConfigId = null;
    public ?string $deleteSelectedConfigBuDeptName = null;

    protected $listeners = ['remount' => 'mount'];

    public function mount()
    {
        $this->currentConfigurations = new Collection();

        $this->name = preg_replace('/ - [^-]+$/', '', $this->helpTopic->name);
        $this->sla = $this->helpTopic->service_level_agreement_id;
        $this->serviceDepartment = $this->helpTopic->service_department_id;
        $this->team = $this->helpTopic->team_id;
        $this->amount = $this->helpTopic->specialProject ? (float) $this->helpTopic->specialProject->amount : null;
        $this->isSpecialProject = $this->helpTopic->specialProject ? true : false;
        $this->fetchCostingApprovers();
        $this->loadCurrentConfigurations();

        $this->buDepartments = $this->queryBUDepartments()->except($this->helpTopic->configurations->pluck('bu_department_id')->toArray());

        $this->costingApprovers = is_array($this->helpTopic->costing?->costing_approvers)
            ? $this->helpTopic->costing->costing_approvers
            : json_decode($this->helpTopic->costing?->costing_approvers, true);

        $this->finalCostingApprovers = is_array($this->helpTopic->costing?->final_costing_approvers)
            ? $this->helpTopic->costing->final_costing_approvers
            : json_decode($this->helpTopic->costing?->final_costing_approvers, true);

        $this->teams = Team::whereHas('serviceDepartment', function ($query) {
            $query->where('service_department_id', $this->helpTopic->service_department_id);
        })->get(['id', 'name']);
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

                    HelpTopicCosting::updateOrCreate(
                        ['help_topic_id' => $this->helpTopic->id],
                        [
                            'costing_approvers' => $this->costingApprovers,
                            'amount' => $this->amount,
                            'final_costing_approvers' => $this->finalCostingApprovers,
                        ]
                    );
                } else {
                    SpecialProject::where('help_topic_id', $this->helpTopic->id)->delete();
                }

                foreach ($this->addedConfigurations as $config) {
                    $helpTopicConfiguration = HelpTopicConfiguration::create([
                        'help_topic_id' => $this->helpTopic->id,
                        'bu_department_id' => $config['bu_department_id'],
                        'level_of_approval' => $config['level_of_approval']
                    ]);

                    foreach ($config['approvers'] as $level => $approversList) {
                        $levelNumber = str_replace('level', '', $level);
                        foreach ($approversList as $approverId) {
                            HelpTopicApprover::create([
                                'help_topic_configuration_id' => $helpTopicConfiguration->id,
                                'help_topic_id' => $this->helpTopic->id,
                                'level' => $levelNumber,
                                'user_id' => $approverId,
                            ]);
                        }
                    }
                }
            });

            $this->emit('remount');
            $this->addedConfigurations = [];
            noty()->addSuccess('Help topic successfully updated.');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', function ($team) {
            $team->where('service_department_id', $this->serviceDepartment);
        })->get(['id', 'name']);

        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', [
            'teams' => $this->teams
        ]);
    }

    public function fetchCostingApprovers()
    {
        $users = User::with(['profile', 'roles'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->get();

        $this->costingApproversList = $users->map(function ($user) {
            return [
                'label' => "{$user->profile->first_name} {$user->profile->last_name}",
                'value' => $user->id,
                'description' => $user->roles->pluck('name')->join(', ')
            ];
        })->toArray();

        $this->finalCostingApproversList = $this->costingApproversList;
    }

    public function loadCurrentConfigurations()
    {
        return $this->currentConfigurations = $this->helpTopic->configurations()->with('buDepartment')->get();
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

        $filteredApprovers = User::with(['profile', 'roles', 'buDepartments'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', $selectedApprovers)
            ->orderByDesc('created_at')
            ->get();

        $this->dispatchBrowserEvent('load-approvers2', [
            'approvers' => $filteredApprovers,
            'level' => $level
        ]);
    }

    public function saveConfiguration()
    {
        if (!$this->selectedBuDepartment) {
            $this->addError('selectedBuDepartment', 'BU department field is required.');
            return;
        } else {
            $this->resetValidation('selectedBuDepartment');
        }

        if (!$this->levelOfApproval) {
            $this->addError('levelOfApproval', 'Level of approval field is required.');
            return;
        } else {
            $this->resetValidation('levelOfApproval');
        }

        $approvers = [
            'level1' => array_map('intval', $this->level1Approvers),
            'level2' => array_map('intval', $this->level2Approvers),
            'level3' => array_map('intval', $this->level3Approvers),
            'level4' => array_map('intval', $this->level4Approvers),
            'level5' => array_map('intval', $this->level5Approvers),
        ];

        $approversCount = array_sum(array_map('count', $approvers));

        // Get the selected BU Department name
        $buDepartmentName = collect($this->buDepartments)
            ->where('id', $this->selectedBuDepartment)
            ->pluck('name')
            ->first();

        // Add to the configurations array
        $this->addedConfigurations[] = [
            'bu_department_id' => $this->selectedBuDepartment,
            'bu_department_name' => $buDepartmentName,
            'approvers_count' => $approversCount,
            'approvers' => $approvers,
            'level_of_approval' => $this->levelOfApproval
        ];

        $this->resetApprovalConfigFields();
    }

    private function resetApprovalConfigFields()
    {
        $this->selectedBuDepartment = null;
        $this->levelOfApproval = null;
        $this->level1Approvers = [];
        $this->level2Approvers = [];
        $this->level3Approvers = [];
        $this->level4Approvers = [];
        $this->level5Approvers = [];
        $this->dispatchBrowserEvent('reset-select-fields');
    }

    public function editCurrentConfiguration(HelpTopicConfiguration $helpTopicConfiguration)
    {
        $this->currentHelpTopicConfiguration = $helpTopicConfiguration;
        $this->currentConfigBuDepartment = $helpTopicConfiguration->buDepartment;

        $helpTopicApproverQuery = HelpTopicApprover::where([
            ['help_topic_configuration_id', $helpTopicConfiguration->id],
            ['help_topic_id', $helpTopicConfiguration->helpTopic->id]
        ])
            ->withWhereHas('approver', function ($approver) {
                $approver->with('profile')
                    ->withWhereHas('buDepartments', function ($department) {
                        $department->where('departments.id', $this->currentConfigBuDepartment->id);
                    });
            });

        $currentConfigApproverIds = $helpTopicApproverQuery->pluck('user_id')->toArray();
        $currentConfigLevelOfApproval = $helpTopicApproverQuery->with('configuration')->first()->configuration->level_of_approval;

        $buDepartmentApprovers = User::with('profile')
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->withWhereHas('buDepartments', function ($department) {
                $department->where('departments.id', $this->currentConfigBuDepartment->id);
            })->get();

        $this->dispatchBrowserEvent('load-current-configuration', [
            'currentConfigApproverIds' => $currentConfigApproverIds,
            'buDepartmentApprovers' => $buDepartmentApprovers,
            'currentConfigLevelOfApproval' => $currentConfigLevelOfApproval
        ]);
    }

    public function updateCurrentConfiguration()
    {
        try {
            if (!is_null($this->currentHelpTopicConfiguration) && !empty($this->selectedApprovers)) {
                $this->currentHelpTopicConfiguration->approvers()->delete();

                foreach ($this->selectedApprovers as $approver) {
                    $this->currentHelpTopicConfiguration->approvers()->create([
                        'help_topic_id' => $this->helpTopic->id,
                        'user_id' => (int) $approver,
                        'level' => 1
                    ]);
                }

                $this->emitSelf('remount');
                $this->dispatchBrowserEvent('close-update-current-config-modal');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    // public function editConfiguration(HelpTopicConfiguration $helpTopicConfiguration)
    // {
    //     $configApprovers = $helpTopicConfiguration->approvers()->with('approver.profile')->pluck('user_id')->toArray();
    //     $helpTopicApprovers = HelpTopicApprover::with('approver.profile')
    //         ->whereNotIn('id', $configApprovers)
    //         ->where('help_topic_configuration_id', $helpTopicConfiguration->id)
    //         ->get();

    //     $this->dispatchBrowserEvent('get-helptopic-co-approvers', [
    //         'helpTopicApprovers' => $helpTopicApprovers
    //     ]);
    // }

    public function deleteAddedConfig(int $index)
    {
        array_splice($this->addedConfigurations, $index, 1);
    }

    public function confirmDeleteCurrentConfiguration(HelpTopicConfiguration $helpTopicConfiguration)
    {
        $this->deleteSelectedConfigId = $helpTopicConfiguration->id;
        $this->deleteSelectedConfigBuDeptName = $helpTopicConfiguration->buDepartment->name;
    }

    public function deleteConfiguration()
    {
        try {
            $configuration = HelpTopicConfiguration::findOrFail($this->deleteSelectedConfigId);
            $configuration->delete();

            $this->reset(['deleteSelectedConfigId', 'deleteSelectedConfigBuDeptName']);
            $this->dispatchBrowserEvent('close-confirm-delete-config-modal');
            $this->emit('remount');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancelDeleteConfiguration()
    {
        $this->reset(['deleteSelectedConfigId', 'deleteSelectedConfigBuDeptName']);
        $this->dispatchBrowserEvent('close-confirm-delete-config-modal');
        $this->emitSelf('remount');
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
