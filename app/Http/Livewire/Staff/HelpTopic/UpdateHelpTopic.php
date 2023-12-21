<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public $teams = [];
    public $name;
    public $sla;
    public $service_department;
    public $team;
    public $level_of_approval;
    public $amount;
    public $max_amount = 50000;
    public $fpmCOOApprover;
    public $currentLevelOfApproval;

    public function mount(HelpTopic $helpTopic)
    {
        $this->name = $helpTopic->name;
        $this->sla = $helpTopic->service_level_agreement_id;
        $this->service_department = $helpTopic->service_department_id;
        $this->team = $helpTopic->team_id;
        $this->amount = $helpTopic->specialProject ? $helpTopic->specialProject->amount : null;
        $this->level_of_approval = $helpTopic->levels->pluck('id')->last();
        $this->fpmCOOApprover = $this->helpTopic->specialProject->fmp_coo_approver['approver_id'] ?? null;
        $this->currentLevelOfApproval = $helpTopic->levels->pluck('id')->last();
        $this->teams = Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $helpTopic->service_department_id))->get();
    }

    public function rules()
    {
        $rules = [
            'name' => "required|unique:help_topics,name,{$this->helpTopic->id}",
            'sla' => 'required',
            'service_department' => 'required',
            'team' => 'nullable',
            'level_of_approval' => 'nullable',
            'amount' => 'nullable',
            'teams' => '',
        ];

        // if (!is_null($this->helpTopic->specialProject)) {
        //     $rules['amount'] = 'required';
        //     $rules['level_of_approval'] = 'required';

        //     for ($count = 1; $count <= 5; $count++) {
        //         if (empty($this->{"level{$count}Approvers"})) {
        //             $rules["level{$count}Approvers"] = 'required';
        //         }
        //     }
        // }

        return $rules;
    }

    public function updateHelpTopic()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->helpTopic->update([
                    'service_department_id' => $this->service_department,
                    'team_id' => $this->team,
                    'service_level_agreement_id' => $this->sla,
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name),
                ]);
            });

            flash()->addSuccess('Help topic successfully updated.');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-from-selected-service-department', ['teams' => $this->teams]);
    }

    public function render()
    {
        return view('livewire.staff.help-topic.update-help-topic', [
            'serviceLevelAgreements' => $this->queryServiceLevelAgreements(),
            'serviceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
