<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadTicketClarificationsCount extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClarificationCount' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.ticket.load-ticket-clarifications-count');
    }
}