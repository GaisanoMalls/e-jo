<?php

namespace App\Http\Controllers\Staff\Agent;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Status;
use App\Models\Ticket;

class AgentTicketController extends Controller
{
    public function claimTicket(Ticket $ticket)
    {
        try {
            $existingAgentId = Ticket::where('id', $ticket->id)->value('agent_id');

            if (!is_null($existingAgentId)) {
                return back()->with('error', 'Ticket already claimed by another agent. Select another ticket to claim.');
            }

            $ticket->update([
                'agent_id' => auth()->user()->id,
                'status_id' => Status::CLAIMED
            ]);

            ActivityLog::make($ticket->id, 'claimed the ticket');

            return back()->with('success', 'You have claimed the ticket.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to claim the ticket. Please try again.');
        }
    }

    public function ticketDetialsClaimTicket(Ticket $ticket)
    {
        try {
            $existingAgentId = Ticket::where('id', $ticket->id)->value('agent_id');

            if (!is_null($existingAgentId)) {
                return back()->with('error', 'Ticket already claimed. Select another ticket to claim.');
            }

            $ticket->update([
                'agent_id' => auth()->user()->id,
                'status_id' => Status::CLAIMED
            ]);

            ActivityLog::make($ticket->id, 'claimed the ticket');

            return back()->with('success', 'The have successfully claimed the ticket.');

        } catch (\Exception $e) {
            return back()->with('info', 'Failed to claim the ticket. Please try again.');
        }
    }

    public function closeTicket(Ticket $ticket)
    {
        try {
            $ticket->update([
                'status_id' => Status::CLOSED
            ]);

            ActivityLog::make($ticket->id, 'closed the ticket');

            return back()->with('success', 'The have successfully closed the ticket.');

        } catch (\Exception $e) {
            return back()->with('info', 'Failed to close the ticket. Please try again.');
        }
    }
}