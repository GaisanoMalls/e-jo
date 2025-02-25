<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Clarification;
use App\Models\Ticket;
use Livewire\Component;

class LatestClarification extends Component
{
    public Ticket $ticket;
    public $latestClarification;

    protected $listeners = ['loadLatestClarification' => '$refresh'];

    public function render()
    {
        $this->latestClarification = Clarification::where('ticket_id', $this->ticket->id)
            ->whereNot('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->first();

        return view('livewire.staff.ticket.latest-clarification');
    }
}