<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\BasicModelQueries;
use App\Models\Role;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;

class AssignTicket extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $agents, $teams, $agent, $team;
    public $currentTeam, $currentAgent;

    public function mount()
    {
        $this->agents = $this->fetchAgents();
        $this->teams = $this->fetchTeams();
        $this->currentTeam = $this->ticket->team_id;
        $this->currentAgent = $this->ticket->agent_id;
    }

    public function fetchAgents()
    {
        return User::whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->ticket->serviceDepartment->id))
            ->whereHas('branch', fn($branch) => $branch->where('branch_id', $this->ticket->branch->id))->get();
    }

    public function fetchTeams()
    {
        return Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->ticket->serviceDepartment->id))
            ->whereHas('branches', fn($branch) => $branch->where('branches.id', $this->ticket->branch->id))->get();
    }

    public function saveAssignTicket()
    {
        try {
            $this->ticket->update([
                'team_id' => $this->team,
                'agent_id' => $this->agent
            ]);

            $this->resetValidation();
            $this->emit('loadTicketDetails');
            $this->dispatchBrowserEvent('close-modal');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-ticket');
    }
}