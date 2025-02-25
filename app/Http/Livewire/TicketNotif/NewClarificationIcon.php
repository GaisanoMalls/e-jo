<?php

namespace App\Http\Livewire\TicketNotif;

use App\Models\Clarification;
use App\Models\Ticket;
use Livewire\Component;

class NewClarificationIcon extends Component
{
    public Ticket $ticket;
    public bool $ticketHasNewClarification = false;

    protected $listeners = ['loadNewClarificationIcon' => '$refresh'];

    public function render()
    {
        $latestClarification = Clarification::where('ticket_id', $this->ticket->id)
            ->orderByDesc('created_at')
            ->first();

        $this->ticketHasNewClarification = $latestClarification && $latestClarification->user_id != auth()->user()->id;

        return view('livewire.ticket-notif.new-clarification-icon');
    }
}
