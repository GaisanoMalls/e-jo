<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadClarificationsCount extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClarificationsCount' => 'render'];

    public function render()
    {
        return view('livewire.requester.ticket.load-clarifications-count');
    }
}