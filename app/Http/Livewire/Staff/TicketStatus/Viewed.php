<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class Viewed extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $viewedTickets = $this->getViewedTickets();
        return view('livewire.staff.ticket-status.viewed', compact('viewedTickets'));
    }
}