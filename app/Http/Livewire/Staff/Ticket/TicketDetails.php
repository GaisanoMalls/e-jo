<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketDetails extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => '$refresh'];

    private function actionOnSubmit()
    {
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
    }

    public function removeAssignedTeam()
    {
        if (Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
            $this->ticket->update(['team_id' => null]);
            $this->removeAssignedAgent();
            $this->actionOnSubmit();
            ActivityLog::make(ticket_id: $this->ticket->id, description: 'removed the team assigned on this ticket');
        } else {
            noty()->addWarning('You are not allowed to remove the assigned team');
        }
    }

    public function removeAssignedAgent()
    {
        if (Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
            $this->ticket->update([
                'agent_id' => null,
                'status_id' => Status::APPROVED,
            ]);

            $this->actionOnSubmit();
            ActivityLog::make(ticket_id: $this->ticket->id, description: 'removed the agent assigned on this ticket');
        } else {
            noty()->addWarning('You are not allowed to remove the assigned agent');
        }
    }

    public function render()
    {
        if ($this->isSlaOverdue($this->ticket)) {
            $this->ticket->update(['status_id', Status::OVERDUE]);
        }
        return view('livewire.staff.ticket.ticket-details');
    }
}
