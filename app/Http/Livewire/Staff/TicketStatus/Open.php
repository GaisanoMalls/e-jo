<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class Open extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        return view('livewire.staff.ticket-status.open', compact('openTickets'));
    }
}