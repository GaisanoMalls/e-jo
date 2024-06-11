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
    public $service_department;
    public $team;
    public $amount; // For Special project

    // Approval Configuration
    public $approvalLevels = [1, 2, 3, 4, 5];
    public $levelApprovers = null;
    public $level1Approvers = [];
    public $level2Approvers = [];
    public $level3Approvers = [];
    public $level4Approvers = [];
    public $level5Approvers = [];
    public $approvalLevelSelected = false;
    public $buDepartment;


    public function rules()
    {
        return [
            'name' => ['required', 'unique:help_topics,name'],
            'sla' => ['required'],
            'service_department' => ['required'],
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
        $this->validate();

        if ($this->isSpecialProject && !$this->selectedServiceDepartmentChildrenId) {
            session()->flash('sub_service_department_error', 'Sub-service department is required');
            return;
        }

        try {
            DB::transaction(function () {
                $helpTopic = HelpTopic::create([
                    'service_department_id' => $this->service_department,
                    'service_dept_child_id' => $this->selectedServiceDepartmentChildrenId,
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name . ($this->selectedServiceDepartmentChildrenName ? " - {$this->selectedServiceDepartmentChildrenName}" : ''),
                    'slug' => Str::slug($this->name),
                ]);

                if ($this->isSpecialProject) {
                    SpecialProject::create([
                        'help_topic_id' => $helpTopic->id,
                        'amount' => $this->amount,
                    ]);
                }
            });

            $this->actionOnSubmit();
            noty()->addSuccess('A new help topic has been created.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedServiceDepartment()
    {
        if ($this->isSpecialProject) {
            $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->service_department)->get(['id', 'name'])->toArray();
            $this->dispatchBrowserEvent('get-service-department-children', ['serviceDepartmentChildren' => $this->serviceDepartmentChildren]);
        } else {
            $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
            $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
        }
    }

    public function showSpecialProjectContainer()
    {
        $this->dispatchBrowserEvent('show-special-project-container');
    }

    public function hideSpecialProjectContainer()
    {
        $this->name = null;
        $this->dispatchBrowserEvent('hide-special-project-container');
    }

    public function specialProject()
    {
        ($this->isSpecialProject)
            ? $this->showSpecialProjectContainer()
            : $this->hideSpecialProjectContainer();
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
        $this->hideSpecialProjectContainer();
    }

    public function updatedLevel1Approvers()
    {
        $this->levelApprovers = User::with(['profile', 'roles'])->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', array_merge($this->level1Approvers, $this->level2Approvers, $this->level3Approvers, $this->level4Approvers, $this->level5Approvers))
            ->orderByDesc('created_at')->get();

        $this->dispatchBrowserEvent('load-approvers', [
            'levelApprovers' => $this->levelApprovers
        ]);
    }

    public function updatedLevel2Approvers()
    {
        $this->levelApprovers = User::with(['profile', 'roles'])->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->whereNotIn('id', array_merge($this->level1Approvers, $this->level2Approvers, $this->level3Approvers, $this->level4Approvers, $this->level5Approvers))
            ->orderByDesc('created_at')->get();

        $this->dispatchBrowserEvent('load-approvers', [
            'levelApprovers' => $this->levelApprovers
        ]);
    }

    public function updatedApprovalLevelSelected()
    {
        $this->levelApprovers = User::with(['profile', 'roles'])->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])->orderByDesc('created_at')->get();
        $this->dispatchBrowserEvent('load-approvers', [
            'levelApprovers' => $this->levelApprovers
        ]);

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
