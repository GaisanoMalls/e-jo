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
    public $agents = [], $team, $agent;

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
                'team_id' => $this->team ?: null,
                'agent_id' => $this->agent ?: null,
            ]);

            $this->actionOnSubmit();

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function updatedTeam()
    {
        $this->agents = User::with('profile')->whereHas('role', fn($agent) => $agent->where('role_id', Role::AGENT))
            ->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->ticket->serviceDepartment->id))
            ->whereHas('branch', fn($branch) => $branch->where('branch_id', $this->ticket->branch->id))
            ->whereHas('teams', fn($team) => $team->where('teams.id', $this->team))->get();

        $this->dispatchBrowserEvent('get-agents-from-team', ['agents' => $this->agents->toArray()]);
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-ticket', [
            'agents' => $this->agents,
            'teams' => Team::whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->ticket->serviceDepartment->id))
                ->whereHas('branches', fn($branch) => $branch->where('branches.id', $this->ticket->branch->id))->get()
        ]);
    }
}