<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\Requester\Tickets;
use App\Models\Ticket;
use Livewire\Component;

class LatestReply extends Component
{
    use Tickets;

    public Ticket $ticket;
    public $latestReply;

    protected $listeners = ['loadLatestReply' => 'render'];

    public function render()
    {
        $this->latestReply = $this->getLatestReply($this->ticket->id);
        return view('livewire.requester.ticket.latest-reply');
    }
}