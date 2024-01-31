<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketCosting extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketCosting' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.ticket.ticket-costing');
    }
}
