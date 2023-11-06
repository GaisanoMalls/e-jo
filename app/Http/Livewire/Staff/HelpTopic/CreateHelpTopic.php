<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Requests\SysAdmin\Manage\HelpTopic\StoreHelpTopicRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;

    public $checked = false;
    public $teams = [], $level1Approvers = [],
    $level2Approvers = [], $level3Approvers = [],
    $level4Approvers = [], $level5Approvers = [];
    public $name, $sla, $service_department, $team, $levelOfApproval;

    public function rules()
    {
        return (new StoreHelpTopicRequest())->rules();
    }

    public function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->emit('loadHelpTopics');
        $this->dispatchBrowserEvent('close-modal');
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

                if ($this->checked) {
                    $levelApprovers = [
                        $this->level1Approvers,
                        $this->level2Approvers,
                        $this->level3Approvers,
                        $this->level4Approvers,
                        $this->level5Approvers,
                    ];

                    for ($level = 1; $level <= $this->levelOfApproval; $level++) {
                        $helpTopic->levels()->attach($level);
                        foreach ($levelApprovers[$level - 1] as $approver) {
                            LevelApprover::create([
                                'level_id' => $level,
                                'user_id' => $approver,
                                'help_topic_id' => $helpTopic->id
                            ]);
                        }
                    }


                }
            });

            $this->actionOnSubmit();
            flash()->addSuccess('A new help topic has been created.');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
    }

    public function updatedName()
    {
        ($this->name === 'Special Project' || $this->name === 'special project')
            ? $this->dispatchBrowserEvent('checkAndShowContainer')
            : $this->dispatchBrowserEvent('checkAndHideContainer');

    }

    public function showSpecialProjectContainer()
    {
        $this->dispatchBrowserEvent('show-special-project-container', ['approvers' => $this->queryApprovers()]);
    }

    public function hideSpecialProjectContainer()
    {
        $this->dispatchBrowserEvent('hide-special-project-container');
    }

    public function specialProject()
    {
        ($this->checked)
            ? $this->showSpecialProjectContainer()
            : $this->hideSpecialProjectContainer();
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
