<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Livewire\Component;

class Viewed extends Component
{
    use Tickets;

    public function render()
    {
        $viewedTickets = $this->getViewedTickets();
        return view('livewire.approver.ticket-status.viewed', compact('viewedTickets'));
    }
}