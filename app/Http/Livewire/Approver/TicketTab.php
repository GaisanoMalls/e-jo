<?php

namespace App\Http\Livewire\Approver;

use App\Http\Traits\Approver\Tickets;
use App\Http\Traits\Utils;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketTab extends Component
{
    use Tickets, Utils;

    public int $countOpenTickets;
    public int $countViewedTickets;
    public int $countApprovedTickets;
    public int $countDisapprovedTickets;
    public int $countOnProcessTickets;
    public int $countClaimedTickets;
    public int $countClosedTickets;

    public function mount()
    {
        $this->countOpenTickets = $this->getOpenTickets()->count();
        $this->countViewedTickets = $this->getViewedTickets()->count();
        $this->countApprovedTickets = $this->getApprovedTickets()->count();
        $this->countDisapprovedTickets = $this->getDisapprovedTickets()->count();
        $this->countOnProcessTickets = $this->getOnProcessTickets()->count();
        $this->countClaimedTickets = $this->getClaimedTickets()->count();
        $this->countClosedTickets = $this->getClosedTickets()->count();
    }

    public function render()
    {
        return view('livewire.approver.ticket-tab');
    }
}