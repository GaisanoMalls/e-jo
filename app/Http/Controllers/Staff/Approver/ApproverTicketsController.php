<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;
use App\Models\Clarification;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApproverTicketsController extends Controller
{
    use ApproverTickets, Utils;

    public function openTickets()
    {
        return (!$this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.statuses.open')
            : abort(403, 'Unauthorized access');
    }

    public function viewedTickets()
    {
        return (!$this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.statuses.viewed')
            : abort(403, 'Unauthorized access');
    }

    public function approvedTickets()
    {
        return (!$this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.statuses.approved')
            : abort(403, 'Unauthorized access');
    }

    public function disapprovedTickets()
    {
        return (!$this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.statuses.disapproved')
            : abort(403, 'Unauthorized access');
    }

    public function onProcessTickets()
    {
        return (!$this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.statuses.on_process')
            : abort(403, 'Unauthorized access');
    }

    public function costingApprovals()
    {
        $ticketsWithCostings = Ticket::withWhereHas('specialProjectAmountApproval', fn($specialProjectCosting) =>
            $specialProjectCosting->whereNotNull(['service_department_admin_approver->approver_id', 'service_department_admin_approver->date_approved'])
                ->whereJsonContains('service_department_admin_approver->is_approved', true))->get();

        return ($this->costingApprover2Only())
            ? view('layouts.staff.approver.ticket.consting_approval', compact([
                'ticketsWithCostings'
            ]))
            : abort(403, 'Unauthorized access');
    }

    public function viewTicketDetails(Ticket $ticket)
    {
        $latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
            ->orderByDesc('created_at')
            ->first();

        return view('layouts.staff.approver.ticket.view_ticket', compact([
            'ticket',
            'latestClarification',
        ]));
    }
}