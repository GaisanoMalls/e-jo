<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class OnProcess extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        return view('livewire.staff.ticket-status.on-process', compact('onProcessTickets'));
    }
}