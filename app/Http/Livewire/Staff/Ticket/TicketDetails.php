<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => '$refresh'];

    public function actionOnSubmit()
    {
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
    }

    public function removeAssignedTeam()
    {
        if (Auth::user()->role_id === Role::SERVICE_DEPARTMENT_ADMIN) {
            $this->ticket->update(['team_id' => null]);
            $this->removeAssignedAgent();
            $this->actionOnSubmit();
            ActivityLog::make($this->ticket->id, 'removed the team assigned on this ticket');
        } else {
            flash()->addError('You are not allowed to remove the assigned team');
        }
    }

    public function removeAssignedAgent()
    {
        if (Auth::user()->role_id === Role::SERVICE_DEPARTMENT_ADMIN) {
            $this->ticket->update([
                'agent_id' => null,
                'status_id' => Status::APPROVED
            ]);
            $this->actionOnSubmit();
            ActivityLog::make($this->ticket->id, 'removed the agent assigned on this ticket');
        } else {
            flash()->addError('You are not allowed to remove the assigned agent');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-details');
    }
}