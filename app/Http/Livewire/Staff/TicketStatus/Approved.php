<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class Approved extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $approvedTickets = $this->getApprovedTickets();
        return view('livewire.staff.ticket-status.approved', compact('approvedTickets'));
    }
}