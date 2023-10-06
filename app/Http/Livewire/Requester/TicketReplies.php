<?php

namespace App\Http\Livewire\Requester;

use App\Models\Ticket;
use Livewire\Component;

class TicketReplies extends Component
{
    public Ticket $ticket;
    public $replies = null;

    protected $listeners = ['loadTicketDiscussions' => '$refresh'];

    public function loadReplies()
    {
        $this->replies = $this->ticket->replies;
    }

    public function getLatestReply()
    {
        $this->emit('loadLatestReply');
    }

    public function render()
    {
        return view('livewire.requester.ticket-replies');
    }
}