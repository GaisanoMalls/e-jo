<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\TicketApprovalLevel;
use App\Models\Branch;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\HelpTopicApprover;
use App\Models\HelpTopicConfiguration;
use App\Models\HelpTopicCosting;
use App\Models\Role;
use App\Models\SpecialProject;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class UpdateHelpTopic extends Component
{
    use BasicModelQueries, TicketApprovalLevel;

    public HelpTopic $helpTopic;
    public Collection $teams;
    public string $name;
    public int $sla;
    public int $serviceDepartment;
    public int $team;
    public ?float $amount = null;
    public bool $isSpecialProject = false;

    // Costing Configuration
    public array|null|Collection $costingApprovers = [];
    public array|null|Collection $finalCostingApprovers = [];
    public array $costingApproversList = [];
    public array $finalCostingApproversList = [];
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
    public array $selectedApprovers = [];
    public bool $selectedApprovalLevel = false;
    public ?int $levelOfApproval = null;

    public array $buDepartments = [];
    public array $branches = [];
    public int $selectedApproversCount = 0;
    public ?int $selectedBuDepartment = null;
    public ?int $selectedBranch = null;
    public ?Collection $currentConfigurations = null;
    public array $addedConfigurations = [];

    // Edit help topic configuration
    public ?Department $currentConfigBuDepartment = null;
    public ?Branch $currentConfigBranch = null;
    public ?HelpTopicConfiguration $currentHelpTopicConfiguration = null;
    public ?int $editLevelOfApproval = null;
    public array $editLevel1Approvers = [];
    public array $editLevel2Approvers = [];
    public array $editLevel3Approvers = [];
    public array $editLevel4Approvers = [];
    public array $editLevel5Approvers = [];
    public array $editSelectedApprovers = [];
    public array $editSelectedLevels = [];

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

        $this->buDepartments = $this->queryBUDepartments()->toArray();
        $this->branches = $this->queryBranches()->toArray();

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
                        'branch_id' => $config['branch_id'],
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

                // Sync Ticket Approvals for all tickets associated with this help topic
                $tickets = Ticket::where('help_topic_id', $this->helpTopic->id)->get();
                foreach ($tickets as $ticket) {
                    $this->syncTicketApprovals($ticket);
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
        return $this->currentConfigurations = $this->helpTopic->configurations()->with(['buDepartment', 'branch'])->get();
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

        $filteredApprovers = User::with(['profile', 'roles', 'buDepartments', 'branches'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', $this->selectedApprovers)
            ->orderByDesc('created_at')
            ->get();

        $this->dispatchBrowserEvent('load-approvers', [
            'approvers' => $filteredApprovers,
            'level' => $level
        ]);
    }

    public function updatedSelectedApprovalLevel()
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

    public function saveConfiguration()
    {
        if (!$this->selectedBuDepartment) {
            $this->addError('selectedBuDepartment', 'BU department field is required.');
            return;
        } else {
            $this->resetValidation('selectedBuDepartment');
        }

        if (!$this->selectedBranch) {
            $this->addError('selectedBranch', 'Branch field is required.');
            return;
        } else {
            $this->resetValidation('selectedBranch');
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

        // Get the selected branch name
        $brachName = collect($this->branches)
            ->where('id', $this->selectedBranch)
            ->pluck('name')
            ->first();

        // Add to the configurations array
        $this->addedConfigurations[] = [
            'bu_department_id' => $this->selectedBuDepartment,
            'branch_id' => $this->selectedBranch,
            'bu_department_name' => $buDepartmentName,
            'branch_name' => $brachName,
            'approvers_count' => $approversCount,
            'approvers' => $approvers,
            'level_of_approval' => $this->levelOfApproval
        ];

        $this->resetApprovalConfigFields();
    }

    private function resetApprovalConfigFields()
    {
        $this->selectedBuDepartment = null;
        $this->selectedBranch = null;
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
        $this->currentConfigBranch = $helpTopicConfiguration->branch;

        $currentConfigLevelOfApproval = HelpTopicApprover::with('configuration')
            ->where([
                ['help_topic_configuration_id', $helpTopicConfiguration->id],
                ['help_topic_id', $helpTopicConfiguration->helpTopic->id]
            ])
            ->withWhereHas('approver', function ($approver) {
                $approver->with('profile')
                    ->withWhereHas('buDepartments', function ($department) {
                        $department->where('departments.id', $this->currentConfigBuDepartment->id);
                    });
            })->first();

        if ($currentConfigLevelOfApproval) {
            $currentConfigLevelOfApproval = $currentConfigLevelOfApproval->configuration->level_of_approval;
        }

        $buDepartmentApprovers = User::with(['profile', 'roles'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->withWhereHas('buDepartments', function ($department) {
                $department->where('departments.id', $this->currentConfigBuDepartment->id);
            })
            ->withWhereHas('branches', function ($branch) {
                $branch->where('branches.id', $this->currentConfigBranch->id);
            })
            ->get();

        $this->dispatchBrowserEvent('edit-load-current-configuration', [
            'buDepartmentApprovers' => $buDepartmentApprovers,
            'currentConfigLevelOfApproval' => $currentConfigLevelOfApproval
        ]);
    }

    private function editGetFilteredApprovers($level)
    {
        $this->editSelectedApprovers = array_merge(
            (array) $this->editLevel1Approvers,
            (array) $this->editLevel2Approvers,
            (array) $this->editLevel3Approvers,
            (array) $this->editLevel4Approvers,
            (array) $this->editLevel5Approvers
        );

        $filteredApprovers = User::with(['profile', 'roles'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->withWhereHas('buDepartments', fn($buDepartment) => $buDepartment->whereIn('departments.id', [$this->currentConfigBuDepartment->id]))
            ->whereNotIn('id', $this->editSelectedApprovers)
            ->orderByDesc('created_at')
            ->get();

        $currentConfigurations = HelpTopicConfiguration::with(['approvers.approver.profile'])
            ->where([
                ['id', $this->currentHelpTopicConfiguration->id],
                ['help_topic_id', $this->helpTopic->id]
            ])
            ->first()
            ->toArray();

        $levelApprovers = $currentConfigurations['approvers']; // Assuming the 'approvers' field holds the levels data
        $approvers = [];

        foreach ($levelApprovers as $approver) {
            $approvalLevel = 'level' . $approver['level'];

            // Ensure the level exists before adding approvers
            if (!isset($approvers[$approvalLevel])) {
                $approvers[$approvalLevel] = [];
            }

            // Append the approver ID to the respective level's list
            $approvers[$approvalLevel][] = $approver['approver']['id'];
        }

        // Replace the approvers with the new structure that maps levels to approver IDs
        $currentConfigurations['approvers'] = $approvers;

        // Filter configurations based on bu_department_id and approvers being non-empty
        $currentEditLevelApprovers = array_filter([$currentConfigurations], function ($arr) {
            return $arr['bu_department_id'] == $this->currentConfigBuDepartment->id && !empty($arr['approvers']);
        });

        // Dispatch event to the frontend with the appropriate data
        $this->dispatchBrowserEvent('edit-load-current-approvers', [
            'level' => $level,
            'approvers' => $filteredApprovers,
            'currentEditLevelApprovers' => $currentEditLevelApprovers
        ]);
    }

    public function updatedEditLevelOfApproval($value)
    {
        $this->editLevelOfApproval = $value;
        $this->editGetFilteredApprovers(1);
    }

    public function updatedEditLevel1Approvers()
    {
        $this->editGetFilteredApprovers(2);
    }

    public function updatedEditLevel2Approvers()
    {
        $this->editGetFilteredApprovers(3);
    }

    public function updatedEditLevel3Approvers()
    {
        $this->editGetFilteredApprovers(4);
    }

    public function updatedEditLevel4Approvers()
    {
        $this->editGetFilteredApprovers(5);
    }

    public function updateCurrentConfiguration()
    {
        try {
            DB::transaction(function () {
                if ($this->currentHelpTopicConfiguration != null) {
                    if (!$this->editLevelOfApproval) {
                        $this->addError('editLevelOfApproval', 'Level of approval field is required');
                        return;
                    } else {
                        $this->resetValidation('editLevelOfApproval');
                    }

                    if (!empty($this->editSelectedLevels)) {
                        foreach ($this->editSelectedLevels as $level) {
                            if (empty($this->{"editLevel{$level}Approvers"})) {
                                session()->flash('edit_level_approver_message', "Level $level approver field is required.");
                                return;
                            }
                        }
                    }

                    if ($this->editLevelOfApproval) {
                        $approvers = [
                            'level1' => array_map('intval', $this->editLevel1Approvers),
                            'level2' => array_map('intval', $this->editLevel2Approvers),
                            'level3' => array_map('intval', $this->editLevel3Approvers),
                            'level4' => array_map('intval', $this->editLevel4Approvers),
                            'level5' => array_map('intval', $this->editLevel5Approvers),
                        ];

                        $selectedApprovers = array_filter($approvers, function ($key) {
                            return in_array(substr($key, -1), $this->editSelectedLevels);
                        }, ARRAY_FILTER_USE_KEY);
                    }

                    $this->currentHelpTopicConfiguration->update([
                        'level_of_approval' => $this->editLevelOfApproval
                    ]);

                    // Delete existing configuration
                    $this->currentHelpTopicConfiguration->approvers()->delete();

                    // Create a new configuration
                    foreach ($selectedApprovers as $level => $approverIds) {
                        foreach ($approverIds as $approverId) {
                            $this->currentHelpTopicConfiguration->approvers()->create([
                                'help_topic_id' => $this->helpTopic->id,
                                'level' => substr($level, -1), // extract the level number from the key
                                'user_id' => $approverId,
                            ]);
                        }
                    }

                    // Sync Ticket Approvals for all tickets associated with this help topic
                    $tickets = Ticket::where('help_topic_id', $this->helpTopic->id)->get();
                    foreach ($tickets as $ticket) {
                        $this->syncTicketApprovals($ticket);
                    }

                    $this->emit('remount');
                    $this->resetEditApprovalConfigFields();
                    redirect()->route('staff.manage.help_topic.edit_details', $this->helpTopic->id);
                }
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    private function resetEditApprovalConfigFields()
    {
        $this->editLevelOfApproval = null;
        $levels = [1, 2, 3, 4, 5];
        foreach ($levels as $level) {
            $this->{"editLevel{$level}Approvers"} = [];
        }
        $this->dispatchBrowserEvent('edit-reset-select-fields');
    }

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
            HelpTopicConfiguration::findOrFail($this->deleteSelectedConfigId)->delete();
            $this->reset(['deleteSelectedConfigId', 'deleteSelectedConfigBuDeptName']);
            $this->dispatchBrowserEvent('close-confirm-delete-config-modal');
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

    public function cancelEditConfiguration()
    {
        redirect()->route('staff.manage.help_topic.edit_details', $this->helpTopic->id);
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
