<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;

    public function getLevel1Approver()
    {
        $ticketApproval = TicketApproval::withWhereHas('ticket', fn($ticket) => $ticket->where('tickets.id', $this->ticket->id))->get();
        return User::with('profile')->whereIn('id', $ticketApproval->pluck('level_1_approver.approver_id')->flatten()->toArray())->get();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-level-approval', [
            'level1Approvers' => $this->getLevel1Approver(),
        ]);
    }
}
