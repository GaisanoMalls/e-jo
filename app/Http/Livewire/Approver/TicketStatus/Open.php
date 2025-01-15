<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Illuminate\Support\Collection;
use Livewire\Component;

class Open extends Component
{
    use Tickets;

    public Collection $openTickets;

    public function mount()
    {
        $this->openTickets = $this->getOpenTickets();
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.open');
    }
}