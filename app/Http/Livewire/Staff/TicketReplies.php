<?php

namespace App\Http\Livewire\Staff;

use App\Models\Ticket;
use Livewire\Component;

class TicketReplies extends Component
{
    public Ticket $ticket;
    public $replies = null;

    public function loadReplies()
    {
        $this->replies = $this->ticket->replies;
    }

    public function render()
    {
        return view('livewire.staff.ticket-replies');
    }
}