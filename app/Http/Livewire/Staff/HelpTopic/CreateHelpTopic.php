<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Requests\SysAdmin\Manage\HelpTopic\StoreHelpTopicRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\ApprovalLevel;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;

    public $teams = [], $levelApprovers = [];
    public $name, $level_of_approval;
    public $sla, $service_department, $team;
    public bool $checked = false;

    public function rules()
    {
        return (new StoreHelpTopicRequest())->rules();
    }

    public function saveHelpTopic()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $helpTopic = HelpTopic::create([
                    'service_department_id' => $this->service_department,
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name)
                ]);

                for ($level = 1; $level <= (int) $this->level_of_approval; $level++) {
                    $helpTopic->levels()->attach($level);

                    foreach ($this->levelApprovers as $approver) {
                        LevelApprover::create([
                            'level_id' => $level,
                            'user_id' => $approver,
                            'help_topic_id' => $helpTopic->id
                        ]);
                    }
                }
            });

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
    }

    public function updatedLevelOfApproval()
    {
        $this->level_of_approval = ApprovalLevel::where('id', $this->level_of_approval)->pluck('id')->first();
        $this->dispatchBrowserEvent('load-approvers', ['approvers' => $this->queryApprovers()]);
    }

    public function updatedName()
    {
        if ($this->name === 'Special Project' || $this->name === 'special project') {
            $this->dispatchBrowserEvent('checkAndShowContainer');
        } else {
            $this->dispatchBrowserEvent('checkAndHideContainer');
        }
    }

    public function showSpecialProjectContainer()
    {
        $this->dispatchBrowserEvent('show-special-project-container');
    }

    public function hideSpecialProjectContainer()
    {
        $this->dispatchBrowserEvent('hide-special-project-container');
    }

    public function specialProject()
    {
        ($this->checked) ? $this->showSpecialProjectContainer() : $this->hideSpecialProjectContainer();
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.staff.help-topic.create-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'levelOfApprovals' => $this->queryLevelOfApprovals(),
            'approvers' => $this->queryApprovers(),
        ]);
    }
}