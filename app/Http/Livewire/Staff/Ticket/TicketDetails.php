<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => 'render'];

    public function actionOnSubmit()
    {
        $this->emit('loadTicketDetails');
    }

    public function removeAssignedTeam()
    {
        $this->ticket->update(['team_id' => null]);
        $this->removeAssignedAgent();
        $this->actionOnSubmit();
    }

    public function removeAssignedAgent()
    {
        $this->ticket->update(['agent_id' => null]);
        $this->actionOnSubmit();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-details');
    }
}