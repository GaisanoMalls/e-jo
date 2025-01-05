<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;
use App\Models\Clarification;
use App\Models\Ticket;

class ApproverTicketsController extends Controller
{
    use ApproverTickets, Utils;

    private function renderTicketView($viewName)
    {
        return (!$this->costingApprover2Only())
            ? view("layouts.staff.approver.ticket.statuses.$viewName")
            : abort(403, 'Unauthorized access');
    }

    public function openTickets()
    {
        return $this->renderTicketView('open');
    }

    public function viewedTickets()
    {
        return $this->renderTicketView('viewed');
    }

    public function approvedTickets()
    {
        return $this->renderTicketView('approved');
    }

    public function disapprovedTickets()
    {
        return $this->renderTicketView('disapproved');
    }

    public function onProcessTickets()
    {
        return $this->renderTicketView('on_process');
    }

    public function costingApprovals()
    {
        return ($this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.consting_approval', [
                'forApprovalCostings' => $this->getForApprovalCostings()
            ])
            : abort(403, 'Unauthorized access');
    }

    public function viewTicketDetails(Ticket $ticket)
    {
        $isCostingAmountNeedCOOApproval = $this->isCostingAmountNeedCOOApproval($ticket);
        $latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
            ->orderByDesc('created_at')
            ->first();

        return view('layouts.staff.approver.ticket.view_ticket', compact([
            'ticket',
            'latestClarification',
            'isCostingAmountNeedCOOApproval'
        ]));
    }
}