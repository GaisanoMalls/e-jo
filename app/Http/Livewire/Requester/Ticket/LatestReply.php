<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Traits\Requester\Tickets;
use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class LatestReply extends Component
{
    use Tickets;

    public Ticket $ticket;
    public ?Reply $latestReply = null;

    protected $listeners = ['loadLatestReply' => 'mount'];

    public function mount()
    {
        $this->latestReply = $this->getLatestReply($this->ticket->id);
    }

    public function render()
    {
        return view('livewire.requester.ticket.latest-reply');
    }
}