<?php

namespace App\Http\Livewire\TicketNotif;

use App\Models\Reply;
use App\Models\Ticket;
use Livewire\Component;

class NewReplyIcon extends Component
{
    public Ticket $ticket;
    public bool $ticketHasNewReply = false;

    protected $listeners = ['loadNewReplyIcon' => '$refresh'];

    public function render()
    {
        $latestReply = Reply::where('ticket_id', $this->ticket->id)
            ->orderByDesc('created_at')
            ->first();

        $this->ticketHasNewReply = $latestReply && $latestReply->user_id != auth()->user()->id;

        return view('livewire.ticket-notif.new-reply-icon');
    }
}
