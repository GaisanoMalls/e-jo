<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class Disapproved extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $disapprovedTickets = $this->getDisapprovedTickets();
        return view('livewire.staff.ticket-status.disapproved', compact('disapprovedTickets'));
    }
}