<?php

namespace App\Http\Livewire\Staff\Teams;

use App\Http\Requests\SysAdmin\Manage\Team\StoreTeamRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Subteam;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateTeam extends Component
{
    use BasicModelQueries;

    public ?array $selectedBranches = [];
    public ?array $addedSubteam = [];
    public ?string $name = null;
    public ?int $selectedServiceDepartment = null;
    public ?string $subteam = null;
    public bool $hasSubteam = false;

    public function rules()
    {
        return (new StoreTeamRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTeamRequest())->messages();
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadTeams');
        $this->dispatchBrowserEvent('clear-select-options');
    }

    public function addSubteam()
    {
        if ($this->hasSubteam) {
            if (!is_null($this->subteam)) {
                $isSubteamExists = Subteam::where('name', $this->subteam)->exists();

                if ($isSubteamExists) {
                    $this->addError('subteam', 'Subteam is already exists');
                } elseif (in_array(strtolower($this->subteam), array_map('strtolower', $this->addedSubteam))) {
                    $this->addError('subteam', 'Subteam is already added');
                } else {
                    array_push($this->addedSubteam, $this->subteam);
                    $this->reset('subteam');
                }
            } else {
                $this->addError('subteam', 'The subteam field is required');
            }
        }
    }

    public function removeSubteam(int $subteam_key)
    {
        foreach (array_keys($this->addedSubteam) as $key) {
            if ($subteam_key === $key) {
                unset($this->addedSubteam[$key]);
            }
        }
    }


    public function updatedHasSubteam()
    {
        // Clear the added subteam inside the array when unchecked.
        if (!empty($this->addedSubteam)) {
            $this->addedSubteam = [];
        }
    }

    public function saveTeam()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                if ($this->hasSubteam) {
                    if (is_null($this->subteam) && empty($this->addedSubteam)) {
                        $this->addError('subteamError', 'Subteam field is required');

                    } elseif (empty($this->addedSubteam) || empty($this->name) && !empty($this->subteam)) {
                        $this->addError('subteamError', 'Please add a subteam');

                    } else {
                        $team = Team::create([
                            'service_department_id' => $this->selectedServiceDepartment,
                            'name' => $this->name,
                            'slug' => Str::slug($this->name),
                        ]);

                        $team->branches()->attach(array_map('intval', $this->selectedBranches));

                        foreach ($this->addedSubteam as $subteam) {
                            $team->subteams()->create(['name' => $subteam]);
                        }

                        $this->actionOnSubmit();
                        noty()->addSuccess('New team has been created.');
                    }
                } else {
                    $team = Team::create([
                        'service_department_id' => $this->selectedServiceDepartment,
                        'name' => $this->name,
                        'slug' => Str::slug($this->name),
                    ]);

                    $team->branches()->attach(array_map('intval', $this->selectedBranches));

                    $this->actionOnSubmit();
                    noty()->addSuccess('New team has been created.');
                }
            });
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
        return view('livewire.staff.teams.create-team', [
            'serviceDepartments' => $this->queryServiceDepartments(),
            'branches' => $this->queryBranches(),
        ]);
    }
}
