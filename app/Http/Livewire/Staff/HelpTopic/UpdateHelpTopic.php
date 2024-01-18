<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use App\Models\SpecialProject;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UpdateHelpTopic extends Component
{
    use BasicModelQueries;

    public HelpTopic $helpTopic;
    public $isSpecialProject = false;
    public $teams = [];
    public $name;
    public $sla;
    public $service_department;
    public $team;
    public $amount;
    public $max_amount = 50000;

    public function mount(HelpTopic $helpTopic)
    {
        $this->name = $helpTopic->name;
        $this->sla = $helpTopic->service_level_agreement_id;
        $this->service_department = $helpTopic->service_department_id;
        $this->team = $helpTopic->team_id;
        $this->amount = $helpTopic->specialProject ? $helpTopic->specialProject->amount : null;
        $this->teams = Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $helpTopic->service_department_id))->get();
        $this->isSpecialProject = $helpTopic->specialProject ? true : false;
    }

    public function rules()
    {
        $rules = [
            'name' => "required|unique:help_topics,name,{$this->helpTopic->id}",
            'sla' => 'required',
            'service_department' => 'required',
            'team' => 'nullable',
            'amount' => $this->helpTopic->specialProject ? 'required' : 'nullable',
            'teams' => '',
        ];

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

                if ($this->helpTopic->specialProject) {
                    SpecialProject::where('help_topic_id', $this->helpTopic->id)->update(['amount' => $this->amount]);
                }
            });

            noty()->addSuccess('Help topic successfully updated.');

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
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
