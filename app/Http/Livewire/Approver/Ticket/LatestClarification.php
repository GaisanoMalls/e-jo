<?php

namespace App\Http\Livewire\Approver\Ticket;

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
        $this->latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $this->ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
            ->orderByDesc('created_at')->first();

        return view('livewire.approver.ticket.latest-clarification');
    }
}
