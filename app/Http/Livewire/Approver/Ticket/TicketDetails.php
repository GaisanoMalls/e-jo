<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => '$refresh'];

    public function render()
    {
        return view('livewire.approver.ticket.ticket-details');
    }
}