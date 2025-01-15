<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Illuminate\Support\Collection;
use Livewire\Component;

class Viewed extends Component
{
    use Tickets;

    public Collection $viewedTickets;

    public function mount()
    {
        $this->viewedTickets = $this->getViewedTickets();
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.viewed');
    }
}