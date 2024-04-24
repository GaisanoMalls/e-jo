<?php

namespace App\Http\Livewire\Staff\Teams;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceDepartmentChildren;
use App\Models\Subteam;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class TeamList extends Component
{
    use BasicModelQueries;

    public $teams = [];
    public $currentSubteams = [];
    public $addedSubteams = [];
    public $editSelectedBranches = [];
    public $serviceDepartmentChildren = [];
    public $currentServiceDeptChild;
    public $selectedServiceDeptChild;
    public $teamEditId;
    public $teamDeleteId;
    public $editSelectedServiceDepartment;
    public $name;
    public $subteam;
    public $subteamEditId;
    public $subteamEditName;
    public $hasSubteam = false;

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

    public function editTeam(Team $team)
    {
        $this->teamEditId = $team->id;
        $this->name = $team->name;
        $this->editSelectedServiceDepartment = $team->service_department_id;
        $this->currentServiceDeptChild = $team->service_dept_child_id;
        $this->editSelectedBranches = $team->branches->pluck('id')->toArray();
        $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->editSelectedServiceDepartment)->get(['id', 'name'])->toArray();
        $this->isCurrentTeamHasSubteams();
        $this->subteams();

        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-team-modal');
        $this->dispatchBrowserEvent('edit-current-service-department', ['serviceDepartmentId' => $this->editSelectedServiceDepartment]);
        $this->dispatchBrowserEvent('edit-current-branches', ['branchIds' => $this->editSelectedBranches]);
    }

    public function isCurrentTeamHasSubteams()
    {
        return Subteam::where('team_id', $this->teamEditId)->exists();
    }

    public function subteams()
    {
        return Subteam::where('team_id', $this->teamEditId)->get();
    }

    public function updatedEditSelectedServiceDepartment()
    {
        $this->serviceDepartmentChildren = ServiceDepartmentChildren::where('service_department_id', $this->editSelectedServiceDepartment)->get()->toArray();
        $this->dispatchBrowserEvent('edit-current-service-department-children', [
            'serviceDepartmentChildren' => $this->serviceDepartmentChildren,
            'currentServiceDeptChild' => $this->currentServiceDeptChild
        ]);
    }

    public function addSubteam()
    {
        try {
            if (!is_null($this->subteam)) {
                $isExistsInDB = Subteam::where('name', $this->subteam)->exists();
                $subteamLowerCase = strtolower($this->subteam);
                $newlyAddedSubteamLowerCase = array_map('strtolower', $this->addedSubteams);

                if ($isExistsInDB) {
                    $this->addError('subteam', 'Subteam is already exists');

                } elseif (in_array($subteamLowerCase, $newlyAddedSubteamLowerCase)) {
                    $this->addError('subteam', 'Subteam is already added');

                } else {
                    array_push($this->addedSubteams, $this->subteam);
                    $this->reset('subteam');
                }
            } else {
                $this->addError('subteam', 'The subteam field is required');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function removeSubteam(int $subteam_key)
    {
        foreach (array_keys($this->addedSubteams) as $key) {
            if ($subteam_key === $key) {
                unset($this->addedSubteams[$key]);
            }
        }
    }

    public function deleteSubteam(Subteam $subteam)
    {
        try {
            $subteam->delete();
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            if (!empty($this->serviceDepartmentChildren) && empty($this->selectedServiceDeptChild)) {
                $this->addError('selectedServiceDeptChild', 'The sub-service department field is required.');
                return;
            }

            if (!empty($this->name) && !empty($this->subteam)) {
                $this->addError('subteam', 'Please add the subteam');
                return;
            }

            $team = Team::findOrFail($this->teamEditId);
            $subteamUpdated = false; // Flag to track if subteam have been updated

            collect($this->addedSubteams)->each(function ($subteam) use ($team, &$subteamUpdated) {
                if ($team->subteams()->where('name', $subteam)->doesntExist()) {
                    $team->subteams()->create(['name' => $subteam]);
                    $subteamUpdated = true; // Set flag to true since a subteam has been added
                }
            });

            if ($subteamUpdated) {
                noty()->addSuccess('A new subteam have been added');
                $this->addedSubteams = [];
            }

            if ($team) {
                DB::transaction(function () use ($team) {
                    $team->update([
                        'name' => $this->name,
                        'service_department_id' => $this->editSelectedServiceDepartment,
                        'service_dept_child_id' => $this->selectedServiceDeptChild ?: null,
                        'slug' => Str::slug($this->name),
                    ]);

                    $team->branches()->sync(array_map('intval', $this->editSelectedBranches));
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


    public function editSubteam(Subteam $subteam)
    {
        try {
            $this->subteamEditId = $subteam->id;
            $this->subteamEditName = $subteam->name;
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancelEditSubteam(Subteam $subteam)
    {
        if ($this->subteamEditId === $subteam->id) {
            $this->subteamEditId = null;
            $this->subteamEditName = '';
        }
    }

    public function updateSubteam(Subteam $subteam)
    {
        try {
            $subteam->update(['name' => $this->subteamEditName]);
            $this->subteamEditId = null;
            $this->subteamEditName = '';
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