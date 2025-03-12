<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class Overdue extends Component
{
    use Tickets;

    public Collection $overdueTickets;

    public function mount()
    {
        $this->overdueTickets = $this->getOverdueTickets();
    }
    public function render()
    {
        return view('livewire.approver.ticket-status.overdue');
    }
}
