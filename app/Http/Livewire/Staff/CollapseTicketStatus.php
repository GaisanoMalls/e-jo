<?php

namespace App\Http\Livewire\Staff;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use Livewire\Component;

class CollapseTicketStatus extends Component
{
    use TicketsByStaffWithSameTemplates;

    protected $listeners = ['loadSidebarCollapseTicketStatus' => '$refresh'];

    public function render()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $claimedTickets = $this->getClaimedTickets();
        $onProcessTickets = $this->getOnProcessTickets();
        $overdueTickets = $this->getOverdueTickets();
        $closedTickets = $this->getClosedTickets();

        return view('livewire.staff.collapse-ticket-status', compact([
            'openTickets',
            'viewedTickets',
            'approvedTickets',
            'disapprovedTickets',
            'claimedTickets',
            'onProcessTickets',
            'overdueTickets',
            'closedTickets',
        ]));
    }
}