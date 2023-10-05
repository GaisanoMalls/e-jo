<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadTicketDiscussionCount extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadDiscussionCount' => 'render'];

    public function render()
    {
        return view('livewire.staff.ticket.load-ticket-discussion-count');
    }
}