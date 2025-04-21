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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class UpdateAgent extends Component
{
    use BasicModelQueries, Utils;

    public User $agent;
    public ?Collection $BUDepartments = null;
    public ?Collection $teams = null;
    public ?Collection $subteams = null;
    public array $currentTeams = [];
    public array $currentSubteams = [];
    public array $selectedTeams = [];
    public array $selectedSubteams = [];
    public array $currentPermissions = [];
    public array $permissions = [];
    public string $first_name;
    public ?string $middle_name = null;
    public string $last_name;
    public ?string $suffix = null;
    public string $email;
    public array $branches = [];
    public int $bu_department;
    public int $service_department;

    public function mount()
    {
        $this->first_name = $this->agent->profile->first_name;
        $this->middle_name = $this->agent->profile->middle_name;
        $this->last_name = $this->agent->profile->last_name;
        $this->suffix = $this->agent->profile->suffix;
        $this->email = $this->agent->email;
        $this->branches = $this->agent->branches->pluck('id')->toArray();
        $this->bu_department = $this->agent->buDepartments->pluck('id')->first();
        $this->service_department = $this->agent->serviceDepartments->pluck('id')->first();
        $this->BUDepartments = Department::withWhereHas('branches', fn($query) => $query->whereIn('branches.id', $this->branches))->get();
        $this->teams = Team::withWhereHas('serviceDepartment', fn($query) => $query->where('service_departments.id', $this->service_department))->get();
        $this->subteams = Subteam::withWhereHas('team', fn($query) => $query->whereIn('teams.id', $this->teams->pluck('id')))->get();
        $this->currentTeams = $this->agent->teams->pluck('id')->toArray();
        $this->currentSubteams = $this->agent->subteams->pluck('id')->toArray();
        $this->currentPermissions = $this->agent->getDirectPermissions()->pluck('name')->toArray();
    }

    public function rules()
    {
        return [
            'branches' => 'required',
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

    /**
     * Updates the list of business unit departments based on the selected branches.
     *
     * This method retrieves all departments associated with the selected branches
     * and updates the `BUDepartments` property. It also dispatches a browser event
     * to send the updated list of business unit departments to the frontend.
     *
     * @return void
     */
    public function updatedBranches()
    {
        // Retrieve departments that are associated with the selected branches
        $this->BUDepartments = Department::withWhereHas('branches', fn($query) => $query->whereIn('branches.id', $this->branches))->get();

        // Dispatch a browser event with the retrieved departments
        $this->dispatchBrowserEvent('get-branch-bu-departments', [
            // Pass the departments collection to the frontend
            'BUDepartments' => $this->BUDepartments,
        ]);
    }

    /**
     * Updates the list of teams based on the selected service department.
     *
     * This method retrieves all teams associated with the selected service department
     * and updates the `teams` property. It also dispatches a browser event to send
     * the updated list of teams to the frontend.
     *
     * @return void
     */
    public function updatedServiceDepartment()
    {
        // Retrieve teams associated with the selected service department.
        $this->teams = Team::withWhereHas('serviceDepartment', fn($query) => $query->where('service_departments.id', $this->service_department))->get();

        // Dispatch a browser event to send the updated list of teams to the frontend.
        $this->dispatchBrowserEvent('get-teams-service-department', [
            // Pass the retrieved teams to the frontend.
            'teams' => $this->teams,
        ]);
    }

    /**
     * Updates the list of subteams based on the selected teams.
     *
     * This method retrieves all subteams associated with the selected teams
     * and updates the `subteams` property. It also dispatches a browser event
     * to send the updated list of subteams to the frontend.
     *
     * @return void
     */
    public function updatedSelectedTeams()
    {
        // Retrieve subteams associated with the selected teams.
        $this->subteams = Subteam::withWhereHas('team', fn($query) => $query->whereIn('teams.id', $this->selectedTeams))->get();

        // Dispatch a browser event to send the updated subteams to the frontend.
        $this->dispatchBrowserEvent('get-subteams', [
            // Pass the retrieved subteams to the frontend.
            'subteams' => $this->subteams
        ]);
    }

    /**
     * Updates the agent's account and associated entities.
     *
     * This method performs the following steps:
     * 1. Validates the input data.
     * 2. Executes a database transaction to:
     *    - Update the agent's email address.
     *    - Synchronize the agent's associations with branches, teams, business unit departments, service departments, and subteams.
     *    - Update the agent's permissions.
     *    - Update the agent's profile with their name, suffix, and slug.
     * 3. Displays a success notification upon successful update.
     * 4. Logs any errors that occur during the process.
     *
     * @return void
     */
    public function updateAgentAccount()
    {
        // Validate the input data before proceeding.
        $this->validate();

        try {
            // Begin a database transaction to ensure atomicity.
            DB::transaction(function () {
                // Update the agent's email address.
                $this->agent->update(['email' => $this->email]);

                // Synchronize the agent's associations with branches, teams, and other entities.
                $this->agent->branches()->sync($this->branches);
                $this->agent->teams()->sync($this->selectedTeams);
                $this->agent->buDepartments()->sync([$this->bu_department]);
                $this->agent->serviceDepartments()->sync([$this->service_department]);
                $this->agent->subteams()->sync(array_map('intval', $this->selectedSubteams));

                // Synchronize the agent's permissions.
                $this->agent->syncPermissions($this->permissions);

                // Update the agent's profile with their name, suffix, and slug.
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

                // Display a success notification to the user.
                noty()->addSuccess("You have successfully updated the account for {$this->agent->profile->getFullName}.");
            });

        } catch (Exception $e) {
            // Log any errors that occur during the process.
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
