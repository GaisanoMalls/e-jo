<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => 'render'];

    public function removeAssingedTeam()
    {
        $this->ticket->update(['team_id' => null]);
        $this->emit('loadTicketDetails');
    }

    public function removeAssignedAgent()
    {
        $this->ticket->update(['agent_id' => null]);
        $this->emit('loadTicketDetails');
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-details');
    }
}