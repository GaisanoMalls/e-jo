<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\Requester\Tickets;
use App\Models\Ticket;
use Livewire\Component;

class LatestClarification extends Component
{
    use Tickets;

    public Ticket $ticket;
    public $latestClarification;

    protected $listeners = ['loadLatestClarification' => '$refresh'];

    public function render()
    {
        $this->latestClarification = $this->getLatestClarification($this->ticket->id);
        return view('livewire.requester.ticket.latest-clarification');
    }
}