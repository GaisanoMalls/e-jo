<?php

namespace App\Http\Livewire\Staff\Teams;

use App\Http\Traits\BasicModelQueries;
use App\Models\Branch;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamList extends Component
{
    use BasicModelQueries;

    public $teams = [];
    public $editSelectedBranches = [];
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
            'editSelectedBranches' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'editSelectedServiceDepartment.required' => 'The service department field is required.',
            'editSelectedBranches.required' => 'The branch field is requied.'
        ];
    }

    public function fetchTeams()
    {
        $this->teams = $this->queryTeams();
    }

    private function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reset-select-options');
    }

    public function editTeam(Team $team)
    {
        $this->teamEditId = $team->id;
        $this->name = $team->name;
        $this->editSelectedServiceDepartment = $team->service_department_id;
        $this->editSelectedBranches = $team->branches->pluck('id')->toArray();

        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-team-modal');
        $this->dispatchBrowserEvent('edit-current-service-department-id', ['serviceDepartmentId' => $this->editSelectedServiceDepartment]);
        $this->dispatchBrowserEvent('edit-current-branch-ids', ['branchIds' => $this->editSelectedBranches]);
    }

    public function update()
    {
        $this->validate();

        try {
            $team = Team::findOrFail($this->teamEditId);

            if ($team) {
                DB::transaction(function () use ($team) {
                    $team->update([
                        'name' => $this->name,
                        'service_department_id' => $this->editSelectedServiceDepartment,
                        'slug' => \Str::slug($this->name),
                    ]);

                    $team->branches()->sync($this->editSelectedBranches);
                });
            }

            $this->actionOnSubmit();

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
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
            sleep(1);
            $this->teamDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Team successfully deleted');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
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