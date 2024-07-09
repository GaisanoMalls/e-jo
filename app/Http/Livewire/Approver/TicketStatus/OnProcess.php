<?php

namespace App\Http\Livewire\Approver\TicketStatus;

use App\Http\Traits\Approver\Tickets;
use Illuminate\Support\Collection;
use Livewire\Component;

class OnProcess extends Component
{
    use Tickets;

    public Collection $onProcessTickets;

    public function mount()
    {
        $this->onProcessTickets = $this->getOnProcessTickets();
    }

    public function render()
    {
        return view('livewire.approver.ticket-status.on-process');
    }
}