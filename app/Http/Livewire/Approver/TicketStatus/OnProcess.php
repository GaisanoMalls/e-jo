<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Livewire\Component;

class OnProcess extends Component
{
    use Tickets;

    public function render()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        return view('livewire.approver.ticket-status.on-process', compact('onProcessTickets'));
    }
}