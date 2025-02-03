<?php

namespace App\Http\Livewire\Approver;

use App\Http\Traits\Approver\Tickets;
use App\Http\Traits\Utils;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketTab extends Component
{
    use Tickets, Utils;

    public Collection $openTickets;
    public Collection $viewedTickets;
    public Collection $approvedTickets;
    public Collection $disapprovedTickets;
    public Collection $onProcessTickets;
    public Collection $closedTickets;
    public Collection $forApprovalTickets;

    public function mount()
    {
        $this->openTickets = $this->getOpenTickets();
        $this->viewedTickets = $this->getViewedTickets();
        $this->approvedTickets = $this->getApprovedTickets();
        $this->disapprovedTickets = $this->getDisapprovedTickets();
        $this->onProcessTickets = $this->getOnProcessTickets();
        $this->closedTickets = $this->getClosedTickets();
    }

    public function render()
    {
        return view('livewire.approver.ticket-tab');
    }
}