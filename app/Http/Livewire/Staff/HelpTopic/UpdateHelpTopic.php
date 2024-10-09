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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;


class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public bool $isSpecialProject = false;
    public Collection $teams;
    public string $name;
    public int $sla;
    public int $serviceDepartment;
    public int $team;
    public ?float $amount = null;

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

    public ?Collection $helpTopicConfigApprovers = null;

    // Delete selected helptopic configuration
    public ?int $deleteSelectedConfigId = null;
    public ?string $deleteSelectedConfigBuDeptName = null;

    protected $listeners = ['remount' => 'mount'];

    public function mount()
    {
        $this->helpTopicConfigApprovers = new Collection();

        $this->name = preg_replace('/ - [^-]+$/', '', $this->helpTopic->name);
        $this->sla = $this->helpTopic->service_level_agreement_id;
        $this->serviceDepartment = $this->helpTopic->service_department_id;
        $this->team = $this->helpTopic->team_id;
        $this->amount = $this->helpTopic->specialProject ? (float) $this->helpTopic->specialProject->amount : null;
        $this->isSpecialProject = $this->helpTopic->specialProject ? true : false;
        $this->buDepartments = $this->queryBUDepartments();

        $this->costingApprovers = is_array($this->helpTopic->costing?->costing_approvers)
            ? $this->helpTopic->costing->costing_approvers
            : json_decode($this->helpTopic->costing?->costing_approvers, true);

        $this->finalCostingApprovers = is_array($this->helpTopic->costing?->final_costing_approvers)
            ? $this->helpTopic->costing->final_costing_approvers
            : json_decode($this->helpTopic->costing?->final_costing_approvers, true);

        $this->teams = Team::whereHas('serviceDepartment', function ($query) {
            $query->where('service_department_id', $this->helpTopic->service_department_id);
        })->get(['id', 'name']);

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
                        'approvers_count' => $config['approvers_count'],
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

    public function loadConfigurations()
    {
        $config = $this->helpTopic->configurations()->with('buDepartment')->get();
        $this->configurations = $config->map(function ($config) {
            return [
                'id' => $config->id,
                'bu_department_id' => $config->bu_department_id,
                'bu_department_name' => $config->buDepartment->name,
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

    public function viewConfigurationApprovers(HelpTopicConfiguration $helpTopicConfiguration)
    {
        $this->helpTopicConfigApprovers = $helpTopicConfiguration->approvers()->with('approver.profile')->get();
    }

    public function editConfiguration(HelpTopicConfiguration $helpTopicConfiguration)
    {
        $helpTopicConfiguration->approvers()->with('approver.profile')->get();
    }

    public function cancelDeleteConfiguration()
    {
        $this->reset(['deleteSelectedConfigId', 'deleteSelectedConfigBuDeptName']);
        $this->dispatchBrowserEvent('close-confirm-delete-config-modal');
        $this->emitSelf('remount');
    }

    public function confirmDeleteConfiguration(HelpTopicConfiguration $helpTopicConfiguration)
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
            $this->emitSelf('remount');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
