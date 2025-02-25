<?php

namespace App\Http\Livewire\Staff;

use App\Models\Ticket;
use Livewire\Component;

class TicketClarifications extends Component
{
    public Ticket $ticket;
    public $clarifications = null;

    protected $listeners = ['loadTicketClarifications' => 'loadClarifications'];

    public function loadClarifications()
    {
        $this->clarifications = $this->ticket->clarifications;
    }

    public function getLatestClarification()
    {
        $this->emit('loadLatestClarification');
    }

    public function render()
    {
        return view('livewire.staff.ticket-clarifications');
    }
}