<?php

namespace App\Http\Livewire\Staff;

use App\Models\Ticket;
use Livewire\Component;

class TicketReplies extends Component
{
    public Ticket $ticket;
    public $replies = null;
    protected $listeners = ['loadTicketReplies' => 'loadReplies'];

    public function loadReplies()
    {
        $this->replies = $this->ticket->replies;
    }

    public function render()
    {
        return view('livewire.staff.ticket-replies');
    }
}