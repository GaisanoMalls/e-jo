<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\Department;
use App\Models\Role;
use App\Models\Subteam;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class UpdateAgent extends Component
{
    use BasicModelQueries, Utils;

    public User $agent;
    public $BUDepartments = [];
    public $teams = [];
    public $currentTeams = [];
    public $currentSubteams = [];
    public $selectedTeams = [];
    public $selectedSubteams = [];
    public $currentPermissions = [];
    public $permissions = [];
    public $subteams = [];
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $branch;
    public $bu_department;
    public $service_department;

    public function mount(User $agent)
    {
        $this->agent = $agent;
        $this->first_name = $agent->profile->first_name;
        $this->middle_name = $agent->profile->middle_name;
        $this->last_name = $agent->profile->last_name;
        $this->suffix = $agent->profile->suffix;
        $this->email = $agent->email;
        $this->branch = $agent->branches->pluck('id');
        $this->bu_department = $agent->buDepartments->pluck('id')->first();
        $this->service_department = $agent->serviceDepartments->pluck('id');
        $this->BUDepartments = Department::withWhereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
        $this->teams = Team::withWhereHas('serviceDepartment', fn($query) => $query->where('service_departments.id', $this->service_department))->get();
        $this->subteams = Subteam::withWhereHas('team', fn($query) => $query->whereIn('teams.id', $this->teams->pluck('id')->toArray()))->get();
        $this->currentTeams = $agent->teams->pluck('id')->toArray();
        $this->currentSubteams = $agent->subteams->pluck('id')->toArray();
        $this->currentPermissions = $this->agent->getDirectPermissions()->pluck('name')->toArray();
    }

    public function rules()
    {
        return [
            'branch' => 'required',
            'bu_department' => 'required',
            'selectedTeams' => 'required',
            'service_department' => 'required',
            'first_name' => 'required|min:2|max:100',
            'middle_name' => 'nullable|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'suffix' => 'nullable|min:1|max:4',
            'email' => "required|max:80|unique:users,email,{$this->agent->id}",
        ];
    }

    public function messages()
    {
        return [
            'selectedTeams.required' => 'Teams field is required.',
        ];
    }

    public function updatedBranch()
    {
        $this->BUDepartments = Department::withWhereHas('branches', fn($query) => $query->where('branches.id', $this->branch))->get();
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
        $this->subteams = Subteam::withWhereHas('team', fn($query) => $query->whereIn('teams.id', $this->selectedTeams))->get();
        $this->dispatchBrowserEvent('get-subteams', [
            'subteams' => $this->subteams
        ]);
    }

    public function updateAgentAccount()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->agent->update(['email' => $this->email]);
                $this->agent->branches()->sync($this->branch);
                $this->agent->teams()->sync($this->selectedTeams);
                $this->agent->buDepartments()->sync($this->bu_department);
                $this->agent->serviceDepartments()->sync($this->service_department);
                $this->agent->subteams()->sync(array_map('intval', $this->selectedSubteams));
                $this->agent->syncPermissions($this->permissions);

                $this->agent->profile()->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify(implode(" ", [
                        $this->first_name,
                        $this->middle_name,
                        $this->last_name,
                        $this->suffix,
                    ])),
                ]);

                noty()->addSuccess("You have successfully updated the account for {$this->agent->profile->getFullName()}.");
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.accounts.agent.update-agent', [
            'agentSuffixes' => $this->querySuffixes(),
            'agentBranches' => $this->queryBranches(),
            'agentServiceDepartments' => $this->queryServiceDepartments(),
            'agentBUDepartments' => $this->BUDepartments,
            'agentTeams' => $this->teams,
            'agentSubteams' => $this->subteams,
            'allPermissions' => Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::AGENT))->get()
        ]);
    }
}
