<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class LoadReason extends Component
{
    public Ticket $ticket;
    public Collection $reason;

    protected $listeners = ['loadReason' => '$refresh'];

    public function mount()
    {
        $this->reason = collect();
    }

    public function render()
    {
        $this->reason = $this->ticket->reasons()
            ->where('ticket_id', $this->ticket->id)
            ->first();

        return view('livewire.approver.ticket.load-reason');
    }
}