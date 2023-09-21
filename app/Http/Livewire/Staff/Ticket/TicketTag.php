<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class TicketTag extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketTags' => 'fetchTicketTags'];

    public function fetchTicketTags()
    {
        $this->ticket->tags;
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-tag');
    }
}