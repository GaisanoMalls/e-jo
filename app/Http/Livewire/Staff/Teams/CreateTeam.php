<?php

namespace App\Http\Livewire\Staff\Teams;

use App\Http\Requests\SysAdmin\Manage\Team\StoreTeamRequest;
use App\Http\Traits\BasicModelQueries;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateTeam extends Component
{
    use BasicModelQueries;

    public $selectedBranches = [];
    public $selectedServiceDepartment, $name;

    public function rules()
    {
        return (new StoreTeamRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTeamRequest())->messages();
    }

    public function updated($fields)
    {
        return $this->validateOnly($fields);
    }

    public function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->emit('loadTeams');
        $this->dispatchBrowserEvent('clear-select-options');
    }

    public function saveTeam()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $team = Team::create([
                    'service_department_id' => $this->selectedServiceDepartment,
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name)
                ]);

                $team->branches()->attach($this->selectedBranches);
            });

            $this->actionOnSubmit();
            flash()->addSuccess('New team has been created.');

        } catch (\Exception $e) {
            flash()->addError('Oops, someting went wrong.');
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