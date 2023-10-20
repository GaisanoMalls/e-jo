<?php

namespace App\Http\Livewire\Staff\Accounts\Agent;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class AgentList extends Component
{
    public $agentDeleteId, $agentFullName;

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
            sleep(1);
            $this->agentDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Requester account has been deleted');

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addSuccess('Oops, something went wrong');
        }
    }

    public function render()
    {
        $agents = User::with(['department', 'branch'])
            ->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->take(5)->orderBy('created_at', 'desc')->get();

        return view('livewire.staff.accounts.agent.agent-list', [
            'agents' => $agents
        ]);
    }
}
