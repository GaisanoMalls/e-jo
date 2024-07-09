<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Approved extends Component
{
    use Tickets;

    public Collection $approvedTickets;

    public function mount()
    {
        $this->approvedTickets = $this->getApprovedTickets();
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.approved');
    }
}