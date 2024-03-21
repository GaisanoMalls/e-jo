<?php

namespace App\Http\Livewire\Staff\Teams;

use App\Http\Requests\SysAdmin\Manage\Team\StoreTeamRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceDepartmentChildren;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateTeam extends Component
{
    use BasicModelQueries;

    public $selectedBranches = [];
    public $serviceDeptChildren = [];
    public $selectedChildren = [];
    public $name;
    public $selectedServiceDepartment;

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

    public function updatedSelectedServiceDepartment()
    {
        $this->serviceDeptChildren = ServiceDepartmentChildren::where('service_department_id', $this->selectedServiceDepartment)->get();
        $this->dispatchBrowserEvent('load-service-department-children', ['serviceDeptChildren' => $this->serviceDeptChildren]);
    }

    public function saveTeam()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $team = Team::create([
                    'service_department_id' => $this->selectedServiceDepartment,
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                ]);

                $team->branches()->attach(array_map('intval', $this->selectedBranches));

                if (!empty ($this->selectedChildren)) {
                    $team->serviceDepartmentChildren()->attach(array_map('intval', $this->selectedChildren));
                }
            });

            $this->actionOnSubmit();
            noty()->addSuccess('New team has been created.');

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
