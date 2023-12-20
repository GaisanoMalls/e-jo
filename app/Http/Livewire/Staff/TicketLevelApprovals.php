<?php

namespace App\Http\Livewire\Staff;

use App\Http\Traits\TicketsByStaffWithSameTemplates;

use Livewire\Component;

class TicketLevelApprovals extends Component
{
    use TicketsByStaffWithSameTemplates;

    public function render()
    {
        $ticketLevelApprovals = $this->getTicketLevelApprovals();
        return view('livewire.staff.ticket-level-approvals', compact('ticketLevelApprovals'));
    }
}
