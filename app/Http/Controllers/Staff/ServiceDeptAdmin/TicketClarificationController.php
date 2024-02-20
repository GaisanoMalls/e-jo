<?php

namespace App\Http\Controllers\Staff\ServiceDeptAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketClarificationController extends Controller
{
    public function __invoke(Ticket $ticket)
    {
        $ticketHasSpecialProject = !is_null($ticket->isSpecialProject());
        return view('layouts.staff.ticket.ticket_clarifications', compact('ticket', 'ticketHasSpecialProject'));
    }
}