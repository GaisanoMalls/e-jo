<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\Requester\Tickets;
use App\Models\Clarification;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class LatestClarification extends Component
{
    use Tickets;

    public Ticket $ticket;
    public ?Clarification $latestClarification = null;

    protected $listeners = ['loadLatestClarification' => 'mount'];

    public function mount()
    {
        $this->latestClarification = $this->getLatestClarification($this->ticket->id);
    }

    public function render()
    {
        return view('livewire.requester.ticket.latest-clarification');
    }
}