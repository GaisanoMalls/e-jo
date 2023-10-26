<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class AgentList extends Component
{
    public $agentDeleteId, $agentFullName;
    public $agents;

    protected $listeners = ['loadAgentList' => '$refresh'];

    public function deleteAgent(User $agent)
    {
        $this->agentDeleteId = $agent->id;
        $this->agentFullName = $agent->profile->getFullName();
        $this->dispatchBrowserEvent('show-delete-agent-modal');
    }

    public function delete()
    {
        try {
            User::find($this->agentDeleteId)->delete();
            $this->agentDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Requester account has been deleted');
            sleep(1);

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    private function getInitialQuery()
    {
        return User::with(['department', 'branch'])
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->take(5)->orderByDesc('created_at')->get();
    }

    public function render()
    {
        $this->agents = (Route::is('staff.manage.user_account.index'))
            ? User::with(['profile', 'department', 'branch'])
                ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
                ->take(5)->orderByDesc('created_at')->get()
            : (
                (Route::is('staff.manage.user_account.agents'))
                ? User::with(['profile', 'department', 'branch'])
                    ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
                    ->orderByDesc('created_at')->get()
                : $this->getInitialQuery()
            );

        return view('livewire.staff.accounts.agent.agent-list', [
            'agents' => $this->agents
        ]);
    }
}
