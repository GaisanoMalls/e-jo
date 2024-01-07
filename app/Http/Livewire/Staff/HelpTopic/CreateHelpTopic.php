<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Requests\SysAdmin\Manage\HelpTopic\StoreHelpTopicRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\SpecialProject;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;
    public $isSpecialProject = false;
    public $COOApprovers = [];
    public $teams = [];
    public $name;
    public $sla;
    public $service_department;
    public $team;
    public $amount; // For Special project
    public $max_amount = 50000;
    public $COOApprover;
    public $serviceDepartmentAdminApprover;

    public function rules()
    {
        return (new StoreHelpTopicRequest())->rules();
    }

    public function actionOnSubmit()
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

        try {
            DB::transaction(function () {
                $helpTopic = HelpTopic::create([
                    'service_department_id' => $this->service_department,
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name),
                ]);

                if ($this->isSpecialProject) {
                    SpecialProject::create([
                        'help_topic_id' => $helpTopic->id,
                        'amount' => $this->amount,
                        'fmp_coo_approver' => [
                            'approver_id' => null,
                            'is_approved' => false,
                        ],
                        'service_department_approver' => [
                            'approver_id' => null,
                            'is_approved' => false,
                        ],
                        'bu_head_approver' => [
                            'approver_id' => null,
                            'is_approved' => false,
                        ],
                    ]);
                }
            });

            $this->actionOnSubmit();
            noty()->addSuccess('A new help topic has been created.');

        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
    }

    public function showSpecialProjectContainer()
    {
        $this->name = 'Special Project';
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
    }

    public function render()
    {
        return view('livewire.staff.help-topic.create-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
