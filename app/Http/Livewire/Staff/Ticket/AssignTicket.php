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
    public $teams, $team, $agents, $agent;

    public function mount()
    {
        $this->agents = $this->fetchAgents();
        $this->teams = $this->fetchTeams();
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

    public function actionOnSubmit()
    {
        sleep(1);
        $this->emit('loadTicketDetails');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function saveAssignTicket()
    {
        try {
            $this->ticket->update([
                'team_id' => $this->team ?? $this->ticket->team_id,
                'agent_id' => $this->agent ?? $this->ticket->agent_id,
            ]);
            $this->actionOnSubmit();

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-ticket');
    }
}