<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketActions extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketActions' => '$refresh'];

    public function getCurrentTeamOrAgent(): void
    {
        if (!is_null($this->ticket->team_id)) {
            $this->dispatchBrowserEvent('get-current-team-or-agent', ['ticket' => $this->ticket]);
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-actions');
    }
}