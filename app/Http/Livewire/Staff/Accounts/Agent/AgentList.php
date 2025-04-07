<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Http\Traits\AppErrorLog;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class AgentList extends Component
{
    use WithPagination;

    public ?int $agentDeleteId = null;
    public ?string $agentFullName = null;
    public ?string $searchAgent = null;

    protected $listeners = ['loadAgentList' => '$refresh'];

    /**
     * Prepares the deletion of an agent and triggers a modal confirmation.
     *
     * This method sets the agent's ID and full name to the respective properties
     * and dispatches a browser event to show the delete confirmation modal.
     *
     * @param User $agent The agent to be deleted.
     * @return void
     */
    public function deleteAgent(User $agent)
    {
        // Set the ID of the agent to be deleted.
        $this->agentDeleteId = $agent->id;

        // Set the full name of the agent to be displayed in the confirmation modal.
        $this->agentFullName = $agent->profile->getFullName;

        // Dispatch a browser event to show the delete agent confirmation modal.
        $this->dispatchBrowserEvent('show-delete-agent-modal');
    }

    /**
     * Deletes the agent account and handles post-deletion actions.
     *
     * This method deletes the agent account identified by the `agentDeleteId` property.
     * After deletion, it resets the `agentDeleteId`, closes the modal, and displays a success notification.
     * If an error occurs during the deletion process, it logs the error.
     *
     * @return void
     */
    public function delete()
    {
        try {
            // Delete the agent account using the stored agent ID.
            User::where('id', $this->agentDeleteId)->delete();

            // Reset the agent ID after successful deletion.
            $this->agentDeleteId = null;

            // Dispatch a browser event to close the modal.
            $this->dispatchBrowserEvent('close-modal');

            // Display a success notification to the user.
            noty()->addSuccess('Requester account has been deleted');
        } catch (Exception $e) {
            // Log the error if an exception occurs during the deletion process.
            AppErrorLog::getError($e->getMessage());
        }
    }

    /**
     * Builds the initial query to search for agents based on various criteria.
     *
     * This method constructs a query to retrieve agents whose profiles, service departments,
     * branches, business units, teams, or subteams match the search term. It also ensures
     * that only users with the "AGENT" role are included in the results, ordered by the
     * most recently created accounts.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator A paginated collection of agents matching the search criteria.
     */
    private function getInitialQuery()
    {
        return User::whereHas('profile', function ($profile) {
            // Search for agents by first name, middle name, or last name.
            $profile->where('first_name', 'like', "%{$this->searchAgent}%")
                ->orWhere('middle_name', 'like', "%{$this->searchAgent}%")
                ->orWhere('last_name', 'like', "%{$this->searchAgent}%");
        })
            // Search for agents by service department name.
            ->orWhereHas('serviceDepartments', fn($serviceDept) => $serviceDept->where('name', 'like', "%{$this->searchAgent}%"))
            // Search for agents by branch name.
            ->orWhereHas('branches', fn($branch) => $branch->where('name', 'like', "%{$this->searchAgent}%"))
            // Search for agents by business unit department name.
            ->orWhereHas('buDepartments', fn($buDept) => $buDept->where('name', 'like', "%{$this->searchAgent}%"))
            // Search for agents by team name.
            ->orWhereHas('teams', fn($team) => $team->where('name', 'like', "%{$this->searchAgent}%"))
            // Search for agents by subteam name.
            ->orWhereHas('subteams', fn($subteam) => $subteam->where('name', 'like', "%{$this->searchAgent}%"))
            // Restrict results to users with the "AGENT" role.
            ->role(Role::AGENT)
            // Order results by the most recently created accounts.
            ->orderByDesc('created_at')
            // Paginate the results with 25 items per page.
            ->paginate(25);
    }

    /**
     * Resets the pagination when the search term is updated.
     *
     * This method ensures that the pagination is reset to the first page
     * whenever the `searchAgent` property is updated. This prevents users
     * from being on a non-existent page after performing a new search.
     *
     * @return void
     */
    public function updatingSearchAgent()
    {
        // Reset the pagination to the first page.
        $this->resetPage();
    }

    /**
     * Clears the agent search input.
     *
     * This method resets the `searchAgent` property to an empty string,
     * effectively clearing the search input and resetting the search filter.
     *
     * @return void
     */
    public function clearAgentSearch()
    {
        // Reset the searchAgent property to an empty string.
        $this->searchAgent = '';
    }

    public function render()
    {
        $agents = $this->getInitialQuery(); // Default query to fetch agents with search filters.

        if (Route::is('staff.manage.user_account.index')) {
            // Fetch all agents with their profiles for the index route.
            $agents = User::with('profile')
                ->role(Role::AGENT)
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        if (Route::is('staff.manage.user_account.agents')) {
            // Apply the initial query with search filters for the agents route.
            $agents = $this->getInitialQuery();
        }

        return view('livewire.staff.accounts.agent.agent-list', [
            'agents' => $agents,
        ]);
    }
}
