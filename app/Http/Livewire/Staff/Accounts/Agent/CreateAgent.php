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

    /**
     * Updates the list of business unit departments based on the selected branches.
     *
     * This method retrieves all departments associated with the selected branches
     * and updates the `BUDepartments` property. It also dispatches a browser event
     * to send the updated list of business unit departments to the frontend.
     *
     * @return void
     */
    public function updatedSelectedBranches()
    {
        // Retrieve departments associated with the selected branches.
        $this->BUDepartments = Department::withWhereHas(
            'branches',
            fn($query) =>
            $query->whereIn('branches.id', $this->selectedBranches)
        )->get();

        // Dispatch a browser event to send the updated departments to the frontend.
        $this->dispatchBrowserEvent('get-branch-bu-departments', [
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
        $this->teams = Team::withWhereHas(
            'serviceDepartment',
            fn($query) =>
            $query->where('service_departments.id', $this->service_department)
        )->get();

        // Dispatch a browser event to send the updated teams to the frontend.
        $this->dispatchBrowserEvent('get-teams-service-department', [
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
        $this->subteams = Subteam::withWhereHas(
            'team',
            fn($team) =>
            $team->whereIn('teams.id', array_map('intval', $this->selectedTeams))
        )->get();

        // Dispatch a browser event to send the updated subteams to the frontend.
        $this->dispatchBrowserEvent('get-subteams', [
            'subteams' => $this->subteams
        ]);
    }

    /**
     * Handles post-submission actions after creating or updating an agent.
     *
     * This method performs the following actions:
     * 1. Resets all component properties to their default values.
     * 2. Clears any validation errors.
     * 3. Emits the `loadAgentList` event to refresh the agent list.
     * 4. Dispatches a browser event to close the modal window.
     *
     * @return void
     */
    private function actionOnSubmit()
    {
        // Reset all component properties to their default values.
        $this->reset();

        // Clear any validation errors.
        $this->resetValidation();

        // Emit an event to refresh the agent list.
        $this->emit('loadAgentList');

        // Dispatch a browser event to close the modal window.
        $this->dispatchBrowserEvent('close-modal');
    }

    /**
     * Saves a new agent and associates them with the selected entities.
     *
     * This method performs the following steps:
     * 1. Validates the input data.
     * 2. Executes a database transaction to:
     *    - Create a new agent user with the provided email and a default password.
     *    - Assign the "AGENT" role to the user.
     *    - Attach the agent to the selected branches, teams, business unit departments, service departments, and subteams.
     *    - Assign permissions to the agent based on their role.
     *    - Create a profile for the agent with their full name and other details.
     * 3. Calls the `actionOnSubmit` method to reset the component state and close the modal.
     * 4. Logs any errors that occur during the process.
     *
     * @return void
     */
    public function saveAgent()
    {
        // Validate the input data before proceeding.
        $this->validate();

        try {
            // Begin a database transaction to ensure atomicity.
            DB::transaction(function () {
                // Create a new agent user with the provided email and a default password.
                $agent = User::create([
                    'email' => $this->email,
                    'password' => Hash::make('agent'),
                ]);

                // Assign the "AGENT" role to the user.
                $agent->assignRole(Role::AGENT);

                // Attach the agent to the selected branches, teams, and other entities.
                $agent->branches()->attach($this->selectedBranches);
                $agent->teams()->attach($this->selectedTeams);
                $agent->buDepartments()->attach($this->bu_department);
                $agent->serviceDepartments()->attach($this->service_department);
                $agent->subteams()->attach(array_map('intval', $this->selectedSubteams));

                // Assign permissions to the agent based on their role.
                $agent->givePermissionTo(
                    Permission::withWhereHas('roles', fn($role) => $role->where('roles.name', Role::AGENT))
                        ->pluck('name')
                        ->toArray()
                );

                // Construct the agent's full name.
                $fullname = $this->first_name . $this->middle_name ?? "" . $this->last_name;

                // Create a profile for the agent with their full name and other details.
                Profile::create([
                    'user_id' => $agent->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'slug' => $this->slugify($fullname),
                ]);
            });

            // Perform post-submission actions such as resetting the component and closing the modal.
            $this->actionOnSubmit();

        } catch (Exception $e) {
            // Log any errors that occur during the process.
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
