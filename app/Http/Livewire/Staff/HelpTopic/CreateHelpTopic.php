<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Requests\SysAdmin\Manage\HelpTopic\StoreHelpTopicRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\HelpTopic;
use App\Models\LevelApprover;
use App\Models\SpecialProject;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateHelpTopic extends Component
{
    use Utils, BasicModelQueries;
    public $checked = false;
    public $showAmountError = false;
    public $COOApprovers = [];
    public $teams = [];
    public $level1Approvers = [];
    public $level2Approvers = [];
    public $level3Approvers = [];
    public $level4Approvers = [];
    public $level5Approvers = [];
    public $name;
    public $sla;
    public $service_department;
    public $team;
    public $level_of_approval;
    public $amount; // For Special project
    public $max_amount = 50000;
    public $COOApprover;

    public function rules()
    {
        $rules = (new StoreHelpTopicRequest())->rules();

        if ($this->checked) {
            if (is_null($this->level_of_approval)) {
                $rules['level_of_approval'] = ['required'];
            } else {
                $rules['level_of_approval'] = ['nullable'];
            }

            // if (is_null($this->amount)) {
            //     flash()->addError('Please enter an amount.');
            //     $rules['amount'] = ['required'];
            // } else {
            //     $this->showAmountError = false;
            //     $rules['amount'] = ['nullable'];
            // }


        }

        return $rules;
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
                    'slug' => \Str::slug($this->name),
                ]);

                if ($this->checked) {
                    SpecialProject::create([
                        'help_topic_id' => $helpTopic->id,
                        'amount' => $this->amount,
                        'fmp_coo_approver' => [
                            'approver_id' => User::where('id', $this->COOApprover)->exists() ? $this->COOApprover : null,
                            'is_approved' => false,
                        ],
                        // 'service_department_admin_approver' => [
                        //     'service_department_admin_id' => UserServiceDepartment::where('service_department_id', $this->serviceDepartment)->pluck('user_id')->first(),
                        //     'is_approved' => false
                        // ]
                    ]);

                    for ($level = 1; $level <= $this->level_of_approval; $level++) {
                        $helpTopic->levels()->attach($level);
                        $levelApprovers = $this->{'level' . $level . 'Approvers'};
                        foreach ($levelApprovers as $approver) {
                            LevelApprover::create([
                                'level_id' => $level,
                                'user_id' => $approver,
                                'help_topic_id' => $helpTopic->id,
                            ]);
                        }
                    }
                }
            });

            $this->actionOnSubmit();
            flash()->addSuccess('A new help topic has been created.');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function updatedAmount()
    {
        if ((int) $this->amount >= $this->max_amount) {
            $this->dispatchBrowserEvent('show-select-fmp-coo-approver');
        } else {
            $this->dispatchBrowserEvent('hide-select-fmp-coo-approver');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
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
