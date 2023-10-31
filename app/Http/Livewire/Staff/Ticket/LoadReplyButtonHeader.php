<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadReplyButtonHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadReplyButtonHeader' => '$refresh'];

    public function getLatestReply(): void
    {
        $this->emit('loadLatestReply');
    }

    public function render()
    {
        return view('livewire.staff.ticket.load-reply-button-header');
    }
}