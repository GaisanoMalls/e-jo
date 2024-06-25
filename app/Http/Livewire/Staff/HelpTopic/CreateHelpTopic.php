<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
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
    public $amount; // For Special project
    public $costingApprovers = [];
    public $showCostingApproverSelect = false;

    // Approval Configuration
    public $approvalLevels = [1, 2, 3, 4, 5];
    public $levelApprovers = null;
    public $level1Approvers = [];
    public $level2Approvers = [];
    public $level3Approvers = [];
    public $level4Approvers = [];
    public $level5Approvers = [];
    public $approvalLevelSelected; // bool
    public $buDepartment;


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
        $this->dispatchBrowserEvent('close-modal');
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
        if ($this->isSpecialProject) {
            $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $value))->get();
            $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
        } else {
            $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $value))->get();
            $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
        }
    }

    public function updatedAmount($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('show-select-costing-approver');
        } else {
            $this->dispatchBrowserEvent('hide-select-costing-approver');
        }
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

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
        $this->hideSpecialProjectContainer();
    }

    // public function updatedLevel1Approvers($value)
    // {
    //     dump($value);
    // }

    // public function updatedLevel2Approvers()
    // {
    //     $this->levelApprovers = User::with(['profile', 'roles'])->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
    //         ->whereNotIn('id', array_merge($value, $this->level2Approvers, $this->level3Approvers, $this->level4Approvers, $this->level5Approvers))
    //         ->orderByDesc('created_at')->get();

    //     $this->dispatchBrowserEvent('remaining-approvers-from-level2', ['levelApprovers' => $this->levelApprovers]);
    // }

    public function updatedApprovalLevelSelected()
    {
        $this->levelApprovers = User::with(['profile', 'roles'])->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])->orderByDesc('created_at')->get();
        $this->dispatchBrowserEvent('load-initial-approvers', ['levelApprovers' => $this->levelApprovers]);
    }

    public function render()
    {
        return view('livewire.staff.help-topic.create-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'buDepartments' => $this->queryBUDepartments(),
        ]);
    }
}
