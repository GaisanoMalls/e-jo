<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadTicketStatusHeaderText extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketStatusHeaderText' => '$refresh'];

    public function render()
    {
        return view('livewire.approver.ticket.load-ticket-status-header-text');
    }
}