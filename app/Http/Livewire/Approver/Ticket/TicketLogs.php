<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketLogs extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketLogs' => '$refresh'];

    public function render()
    {
        return view('livewire.approver.ticket.ticket-logs');
    }
}