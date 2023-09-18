<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class Closed extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $closedTickets = $this->getClosedTickets();
        return view('livewire.staff.ticket-status.closed', compact('closedTickets'));
    }
}