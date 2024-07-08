<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Livewire\Component;

class TicketDetails extends Component
{
    use Utils;

    public Ticket $ticket;
    public bool $isSlaApproved = false;

    protected $listeners = ['loadTicketDetails' => '$refresh'];

    public function mount()
    {
        $this->isSlaApproved = $this->isSlaApproved($this->ticket);
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-details');
    }
}