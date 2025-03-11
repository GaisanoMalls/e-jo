<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Claimed extends Component
{
    use Tickets;

    public Collection $claimedTickets;

    public function mount()
    {
        $this->claimedTickets = $this->getClaimedTickets();
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.claimed');
    }
}
