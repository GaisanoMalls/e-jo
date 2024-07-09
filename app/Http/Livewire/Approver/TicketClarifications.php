<?php

namespace App\Http\Livewire\Approver;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketClarifications extends Component
{
    public Ticket $ticket;
    public ?Collection $clarifications = null;

    protected $listeners = ['loadClarifications' => '$refresh'];

    public function loadClarifications(): void
    {
        $this->clarifications = $this->ticket->clarifications;
    }

    public function getLatestClarification(): void
    {
        $this->emit('loadLatestClarification');
    }

    public function render()
    {
        return view('livewire.approver.ticket-clarifications');
    }
}
