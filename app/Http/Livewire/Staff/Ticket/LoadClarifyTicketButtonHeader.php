<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadClarifyTicketButtonHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClarificationButtonHeader' => '$refresh'];

    public function getLatestClarification()
    {
        $this->emit('loadLatestClarification');
    }
    public function render()
    {
        return view('livewire.staff.ticket.load-clarify-ticket-button-header');
    }
}