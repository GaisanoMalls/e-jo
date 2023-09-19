<?php

namespace App\Http\Livewire\Staff;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class TicketsToAssign extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $ticketsToAssign = $this->getTicketsToAssign();
        return view('livewire.staff.tickets-to-assign', compact('ticketsToAssign'));
    }
}