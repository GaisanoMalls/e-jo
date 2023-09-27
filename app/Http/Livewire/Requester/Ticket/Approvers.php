<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\LevelApprover;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;

class Approvers extends Component
{
    public Ticket $ticket;

    public function render()
    {
        $levelApprovers = LevelApprover::where('help_topic_id', $this->ticket->helpTopic->id)->get();
        $approvers = User::approvers();

        return view('livewire.requester.ticket.approvers', [
            'levelApprovers' => $levelApprovers,
            'approvers' => $approvers
        ]);
    }
}