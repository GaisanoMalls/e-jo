<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\CountTicketsForTabUse;
use App\Http\Traits\Approver\Tickets;
use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApproverTicketsController extends Controller
{
    use Tickets;

    public function ticketStatusToViewed(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::VIEWED
        ]);

        return response()->json(['message' => 'Ticket viewed']);
    }

    public function openTickets()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $forApprovalTickets = $this->getForApprovalTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.open',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'approvedTickets',
                    'disapprovedTickets',
                    'forApprovalTickets'
                ]
            )
        );
    }

    public function viewedTickets()
    {
        $openTickets = $this->getOpenTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $viewedTickets = $this->getViewedTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.viewed',
            compact(
                [
                    'openTickets',
                    'approvedTickets',
                    'disapprovedTickets',
                    'viewedTickets',
                ]
            )
        );
    }

    public function approvedTickets()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $approvedTickets = $this->getApprovedTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.approved',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'disapprovedTickets',
                    'approvedTickets',
                ]
            )
        );
    }

    public function disapprovedTickets()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();

        $disapprovedTickets = $this->getDisapprovedTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.disapproved',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'approvedTickets',
                    'disapprovedTickets'
                ]
            )
        );
    }

    public function viewTicketDetails($ticketId)
    {
        $latestClarification = Clarification::where('ticket_id', $ticketId)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $ticket = Ticket::with(['clarifications', 'user', 'status'])->where('id', $ticketId)->first();

        return view(
            'layouts.staff.approver.ticket.view_ticket',
            compact([
                'ticket',
                'latestClarification'
            ])
        );
    }

    // * Ticket Actions
    public function approveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::APPROVED,
            'approval_status' => ApprovalStatus::APPROVED
        ]);

        return back()->with('success', 'Ticket was successfully approved.');
    }

    public function disapproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::CLOSED,
            'approval_status' => ApprovalStatus::DISAPPROVED
        ]);

        return back()->with('info', 'Ticket is rejected.');
    }

    public function ticketDetialsApproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::APPROVED,
            'approval_status' => ApprovalStatus::APPROVED
        ]);

        return to_route('approver.ticket.viewTicketDetails', [$ticket->id])->with('success', 'The ticket has been approved.');
    }

    public function ticketDetialsDisapproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::CLOSED,
            'approval_status' => ApprovalStatus::DISAPPROVED
        ]);

        return to_route('approver.ticket.viewTicketDetails', $ticket->id)->with('info', 'The ticket has been disapproved.');
    }

    // * Clarifications
    public function sendClarification(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required'],
            'clarificationFiles.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv', 'max:30000']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTicketClarification')->withInput();

        $clarification = Clarification::create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => $request->input('description')
        ]);

        if ($request->hasFile('clarificationFiles')) {
            foreach ($request->file('clarificationFiles') as $uploadedClarificationFile) {
                $fileName = $uploadedClarificationFile->getClientOriginalName();
                $fileAttachment = $uploadedClarificationFile->storeAs('public/ticket/clarification/files', $fileName);

                $clarificationFile = new ClarificationFile();
                $clarificationFile->file_attachment = $fileAttachment;
                $clarificationFile->clarification_id = $clarification->id;

                $clarification->fileAttachments()->save($clarificationFile);
            }
        }

        return to_route('approver.ticket.viewTicketDetails', $ticket->id)->with('success', 'The message has been successfully sent.');
    }
}