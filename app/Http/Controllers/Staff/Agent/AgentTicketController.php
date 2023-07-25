<?php

namespace App\Http\Controllers\Staff\Agent;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AgentTicketController extends Controller
{
    public function claimTicket(Ticket $ticket)
    {
        $ticket->update([
            'agent_id' => auth()->user()->id,
            'status_id' => Status::CLAIMED
        ]);

        return back()->with('success', 'You have claimed the ticket.');
    }
}