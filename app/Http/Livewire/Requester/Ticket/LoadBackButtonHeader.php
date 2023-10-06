<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadBackButtonHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadBackButtonHeader' => '$refresh'];

    public function render()
    {
        return view('livewire.requester.ticket.load-back-button-header');
    }
}