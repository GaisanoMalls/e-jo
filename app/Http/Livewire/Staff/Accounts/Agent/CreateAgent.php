<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Requests\SysAdmin\Manage\Account\StoreAgenRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\Subteam;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreateAgent extends Component
{
    use BasicModelQueries, Utils;

    public ?Collection $BUDepartments = null;
    public ?Collection $teams = null;
    public ?Collection $subteams = null;
    public array $selectedTeams = [];
    public array $selectedSubteams = [];
    public array $selectedBranches = [];
    public ?string $first_name = null;
    public ?string $middle_name = null;
    public ?string $last_name = null;
    public ?string $suffix = null;
    public ?string $email = null;
    public ?int $bu_department = null;
    public ?int $service_department = null;
    public bool $hasSubteams = false;

    public function rules()
    {
        return (new StoreAgenRequest())->rules();
    }

    public function updatedSelectedBranches()
    {
        $this->BUDepartments = Department::withWhereHas('branches', fn($query) => $query->whereIn('branches.id', $this->selectedBranches))->get();
        $this->dispatchBrowserEvent('get-branch-bu-departments', [
            'BUDepartments' => $this->BUDepartments,
        ]);
    }

    public function updatedServiceDepartment()
    {
        $this->teams = Team::withWhereHas('serviceDepartment', fn($query) => $query->where('service_departments.id', $this->service_department))->get();
        $this->dispatchBrowserEvent('get-teams-service-department', [
            'teams' => $this->teams,
        ]);
    }

    public function updatedSelectedTeams()
    {
        $this->subteams = Subteam::withWhereHas('team', fn($team) => $team->whereIn('teams.id', array_map('intval', $this->selectedTeams)))->get();
        $this->dispatchBrowserEvent('get-subteams', [
            'subteams' => $this->subteams
        ]);
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('loadAgentList');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveAgent()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $agent = User::create([
                    'email' => $this->email,
                    'password' => Hash::make('agent'),
                ]);

                $agent->assignRole(Role::AGENT);
                $agent->branches()->attach($this->selectedBranches);
                $agent->teams()->attach($this->selectedTeams);
                $agent->buDepartments()->attach($this->bu_department);
                $agent->serviceDepartments()->attach($this->service_department);
                $agent->subteams()->attach(array_map('intval', $this->selectedSubteams));
                $agent->givePermissionTo(
                    Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::AGENT))->pluck('name')->toArray()
                );

                $fullname = $this->first_name . $this->middle_name ?? "" . $this->last_name;

                Profile::create([
                    'user_id' => $agent->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify($fullname),
                ]);
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.create-agent', [
            'agentSuffixes' => $this->querySuffixes(),
            'agentBranches' => $this->queryBranches(),
            'agentServiceDepartments' => $this->queryServiceDepartments(),
        ]);
    }
}
