<?php

namespace App\Http\Livewire\Staff\HelpTopic;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\HelpTopic;
use App\Models\ServiceDepartmentChildren;
use App\Models\SpecialProject;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    public $service_department_child;
    public $selected_child;
    public $serviceDepartmentChildren = [];
    public $selectedServiceDepartmentChildrenName;
    public $team;
    public $amount;

    public function mount(HelpTopic $helpTopic)
    {
        $this->helpTopic = $helpTopic;
        $this->name = $helpTopic->name;
        $this->sla = $helpTopic->service_level_agreement_id;
        $this->service_department = $helpTopic->service_department_id;
        $this->team = $helpTopic->team_id;
        $this->amount = $helpTopic->specialProject ? $helpTopic->specialProject->amount : null;
        $this->teams = Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $helpTopic->service_department_id))->get(['id', 'name']);
        $this->isSpecialProject = $helpTopic->specialProject ? true : false;
    }


    public function rules()
    {
        return [
            'name' => "required|unique:help_topics,name,{$this->helpTopic->id}",
            'sla' => 'required',
            'service_department' => 'required',
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
                    'service_department_id' => $this->service_department,
                    'service_dept_child_id' => null, // No sub-service department check
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
                }
            });

            noty()->addSuccess('Help topic successfully updated.');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::whereHas('serviceDepartment', fn($team) => $team->where('service_department_id', $this->service_department))->get(['id', 'name']);
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
