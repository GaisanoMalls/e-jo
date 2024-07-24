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

    public function deleteAgent(User $agent)
    {
        $this->agentDeleteId = $agent->id;
        $this->agentFullName = $agent->profile->getFullName;
        $this->dispatchBrowserEvent('show-delete-agent-modal');
    }

    public function delete()
    {
        try {
            User::where('id', $this->agentDeleteId)->delete();
            $this->agentDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Requester account has been deleted');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    private function getInitialQuery()
    {
        return User::whereHas('profile', function ($profile) {
            $profile->where('first_name', 'like', '%' . $this->searchAgent . '%')
                ->orWhere('middle_name', 'like', '%' . $this->searchAgent . '%')
                ->orWhere('last_name', 'like', '%' . $this->searchAgent . '%');
        })
            ->orWhereHas('serviceDepartments', fn($serviceDept) => $serviceDept->where('name', 'like', '%' . $this->searchAgent . '%'))
            ->orWhereHas('branches', fn($branch) => $branch->where('name', 'like', '%' . $this->searchAgent . '%'))
            ->orWhereHas('buDepartments', fn($buDept) => $buDept->where('name', 'like', '%' . $this->searchAgent . '%'))
            ->orWhereHas('teams', fn($team) => $team->where('name', 'like', '%' . $this->searchAgent . '%'))
            ->orWhereHas('subteams', fn($subteam) => $subteam->where('name', 'like', '%' . $this->searchAgent . '%'))
            ->role(Role::AGENT)
            ->orderByDesc('created_at')
            ->paginate(25);
    }

    public function updatingSearchAgent()
    {
        $this->resetPage();
    }

    public function clearAgentSearch()
    {
        $this->searchAgent = '';
    }

    public function render()
    {
        $agents = $this->getInitialQuery();

        if (Route::is('staff.manage.user_account.index')) {
            $agents = User::with('profile')
                ->role(Role::AGENT)
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        if (Route::is('staff.manage.user_account.agents')) {
            $agents = $this->getInitialQuery();
        }

        return view('livewire.staff.accounts.agent.agent-list', [
            'agents' => $agents,
        ]);
    }
}
