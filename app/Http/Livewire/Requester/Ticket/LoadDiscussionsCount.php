<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadDiscussionsCount extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadDiscussionsCount' => 'render'];

    public function render()
    {
        return view('livewire.requester.ticket.load-discussions-count');
    }
}