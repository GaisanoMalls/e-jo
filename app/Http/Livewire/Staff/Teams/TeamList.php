<?php

namespace App\Http\Livewire\Staff\Teams;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceDepartmentChildren;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class TeamList extends Component
{
    use BasicModelQueries;

    public $teams = [];
    public $editSelectedBranches = [];
    public $currentTeamServiceDeptChildren = [];
    public $serviceDepartmentChildren = [];
    public $selectedServiceDeptChildren = [];
    public $teamEditId;
    public $teamDeleteId;
    public $editSelectedServiceDepartment;
    public $name;

    protected $listeners = ['loadTeams' => 'fetchTeams'];

    protected function rules()
    {
        return [
            'name' => "required|unique:teams,name,{$this->teamEditId}",
            'editSelectedServiceDepartment' => 'required',
            'editSelectedBranches' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'editSelectedServiceDepartment.required' => 'The service department field is required.',
            'editSelectedBranches.required' => 'The branch field is requied.',
        ];
    }

    public function fetchTeams()
    {
        $this->teams = $this->queryTeams();
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reset-select-options');
    }

    // public function editTeam(Team $team)
    // {
    //     $this->teamEditId = $team->id;
    //     $this->name = $team->name;
    //     $this->editSelectedServiceDepartment = $team->service_department_id;
    //     $this->editSelectedBranches = $team->branches->pluck('id')->toArray();
    //     $this->currentTeamServiceDeptChildren = $team->serviceDepartmentChildren->pluck('id')->toArray();
    //     $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->editSelectedServiceDepartment)->get(['id', 'name'])->toArray();

    //     $this->resetValidation();
    //     $this->dispatchBrowserEvent('show-edit-team-modal');
    //     $this->dispatchBrowserEvent('edit-current-service-department', ['serviceDepartmentId' => $this->editSelectedServiceDepartment]);
    //     $this->dispatchBrowserEvent('edit-current-branches', ['branchIds' => $this->editSelectedBranches]);
    //     $this->dispatchBrowserEvent('edit-current-service-department-children', [
    //         'currentTeamServiceDeptChildren' => $this->currentTeamServiceDeptChildren,
    //         'serviceDepartmentChildren' => $this->serviceDepartmentChildren,
    //     ]);
    // }

    // public function updatedEditSelectedServiceDepartment()
    // {
    //     $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->editSelectedServiceDepartment)->get(['id', 'name'])->toArray();
    //     $this->dispatchBrowserEvent('load-service-dept-children', ['serviceDepartmentChildren' => $this->serviceDepartmentChildren]);
    // }

    public function editTeam(Team $team)
    {
        $this->teamEditId = $team->id;
        $this->name = $team->name;
        $this->editSelectedServiceDepartment = $team->service_department_id;
        $this->editSelectedBranches = $team->branches->pluck('id')->toArray();
        $this->currentTeamServiceDeptChildren = $team->serviceDepartmentChildren->pluck('id')->toArray();
        $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->editSelectedServiceDepartment)->get(['id', 'name'])->toArray();

        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-team-modal');
        $this->dispatchBrowserEvent('edit-current-service-department', ['serviceDepartmentId' => $this->editSelectedServiceDepartment]);
        $this->dispatchBrowserEvent('edit-current-branches', ['branchIds' => $this->editSelectedBranches]);

        // Call the function to update child options and selection
        $this->updateServiceDeptChildren($this->serviceDepartmentChildren, $this->currentTeamServiceDeptChildren);
    }

    public function updatedEditSelectedServiceDepartment()
    {
        $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->editSelectedServiceDepartment)->get(['id', 'name'])->toArray();

        // Call the function to update child options and selection
        $this->updateServiceDeptChildren($this->serviceDepartmentChildren, []);
    }

    public function updateServiceDeptChildren($serviceDepartmentChildren, $currentTeamServiceDeptChildren)
    {
        $this->dispatchBrowserEvent('edit-current-service-department-children', [
            'currentTeamServiceDeptChildren' => $currentTeamServiceDeptChildren,
            'serviceDepartmentChildren' => $serviceDepartmentChildren,
        ]);
    }

    public function update()
    {
        $this->validate();

        try {
            $team = Team::find($this->teamEditId);

            if ($team) {
                DB::transaction(function () use ($team) {
                    $team->update([
                        'name' => $this->name,
                        'service_department_id' => $this->editSelectedServiceDepartment,
                        'slug' => Str::slug($this->name),
                    ]);

                    $team->branches()->sync(array_map('intval', $this->editSelectedBranches));
                    $team->serviceDepartmentChildren()->sync(array_map('intval', $this->selectedServiceDeptChildren));
                });
            }

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteTeam(Team $team)
    {
        $this->teamDeleteId = $team->id;
        $this->name = $team->name;
        $this->dispatchBrowserEvent('show-delete-team-modal');
    }

    public function delete()
    {
        try {
            Team::find($this->teamDeleteId)->delete();
            $this->teamDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Team successfully deleted');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('clear-select-options');
    }

    public function render()
    {
        return view('livewire.staff.teams.team-list', [
            'teams' => $this->fetchTeams(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'branches' => $this->queryBranches(),
        ]);
    }
}