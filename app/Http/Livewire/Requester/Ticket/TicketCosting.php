<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Livewire\Component;

class TicketCosting extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = [
        'loadRequesterTicketCosting' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.requester.ticket.ticket-costing');
    }
}
