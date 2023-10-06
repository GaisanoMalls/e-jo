<?php

namespace App\Http\Livewire\Approver;

use App\Models\Ticket;
use Livewire\Component;

class TicketClarifications extends Component
{
    public Ticket $ticket;
    public $clarifications = null;

    protected $listeners = ['loadClarifications' => '$refresh'];

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
        return view('livewire.approver.ticket-clarifications');
    }
}