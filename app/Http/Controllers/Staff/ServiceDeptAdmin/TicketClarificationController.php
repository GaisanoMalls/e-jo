<?php

namespace App\Http\Controllers\Staff\ServiceDeptAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class TicketClarificationController extends Controller
{
    public function __invoke(Ticket $ticket)
    {
        $ticketHasSpecialProject = !is_null($ticket->isSpecialProject());
        $requester = $ticket->user()->with('profile')->withTrashed()->first();

        return view('layouts.staff.ticket.ticket_clarifications', compact([
            'ticket',
            'ticketHasSpecialProject',
            'requester'
        ]));
    }
}