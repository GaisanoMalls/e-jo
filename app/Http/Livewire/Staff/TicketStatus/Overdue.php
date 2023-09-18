<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class Overdue extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $overdueTickets = $this->getOverdueTickets();
        return view('livewire.staff.ticket-status.overdue', compact('overdueTickets'));
    }
}