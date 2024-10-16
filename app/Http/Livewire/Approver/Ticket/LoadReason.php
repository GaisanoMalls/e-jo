<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Models\Reason;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class LoadReason extends Component
{
    public Ticket $ticket;
    public $reason;

    protected $listeners = ['loadReason' => 'mount'];

    public function mount()
    {
        $this->reason = Reason::where('ticket_id', $this->ticket->id)->first();
    }

    public function render()
    {
        return view('livewire.approver.ticket.load-reason');
    }
}