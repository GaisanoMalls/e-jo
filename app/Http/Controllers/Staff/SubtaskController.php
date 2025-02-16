<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function __invoke(Ticket $ticket)
    {
        $requester = $ticket->user()->with('profile')->withTrashed()->first();

        return view('layouts.staff.ticket.ticket_subtasks', compact([
            'ticket',
            'requester'
        ]));
    }
}
