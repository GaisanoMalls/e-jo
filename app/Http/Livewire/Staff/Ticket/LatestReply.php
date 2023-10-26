<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Reply;
use App\Models\Ticket;
use Livewire\Component;

class LatestReply extends Component
{

    public Ticket $ticket;
    public $latestReply;

    protected $listeners = ['loadLatestReply' => '$refresh'];

    public function render()
    {
        $this->latestReply = Reply::where('ticket_id', $this->ticket->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderByDesc('created_at')
            ->first();

        return view('livewire.staff.ticket.latest-reply');
    }
}