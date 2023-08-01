<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Traits\Approver\CountTicketsForTabUse;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Reason;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApproverTicketsController extends Controller
{
    use ApproverTickets;

    public function ticketStatusToViewed(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::VIEWED
        ]);

        return response()->json(['message' => 'Ticket viewed']);
    }

    public function openTickets()
    {
        $forApprovalTickets = $this->getForApprovalTickets();
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $onProcessTickets = $this->getOnProcessTickets();

        $forApprovalTickets = $this->getForApprovalTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.open',
            compact(
                [
                    'forApprovalTickets',
                    'openTickets',
                    'viewedTickets',
                    'approvedTickets',
                    'disapprovedTickets',
                    'onProcessTickets',
                    'forApprovalTickets',
                ]
            )
        );
    }

    public function viewedTickets()
    {
        $openTickets = $this->getOpenTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();
        $onProcessTickets = $this->getOnProcessTickets();

        $viewedTickets = $this->getViewedTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.viewed',
            compact(
                [
                    'openTickets',
                    'approvedTickets',
                    'disapprovedTickets',
                    'onProcessTickets',
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
        $onProcessTickets = $this->getOnProcessTickets();

        $approvedTickets = $this->getApprovedTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.approved',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'disapprovedTickets',
                    'onProcessTickets',
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
        $onProcessTickets = $this->getOnProcessTickets();

        $disapprovedTickets = $this->getDisapprovedTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.disapproved',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'approvedTickets',
                    'onProcessTickets',
                    'disapprovedTickets'
                ]
            )
        );
    }

    public function onProcessTickets()
    {
        $openTickets = $this->getOpenTickets();
        $viewedTickets = $this->getViewedTickets();
        $approvedTickets = $this->getApprovedTickets();
        $disapprovedTickets = $this->getDisapprovedTickets();

        $onProcessTickets = $this->getOnProcessTickets();

        return view(
            'layouts.staff.approver.ticket.statuses.on_process',
            compact(
                [
                    'openTickets',
                    'viewedTickets',
                    'approvedTickets',
                    'disapprovedTickets',
                    'onProcessTickets'
                ]
            )
        );
    }

    public function viewTicketDetails(Ticket $ticket)
    {
        $latestClarification = Clarification::where('ticket_id', $ticket->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $reason = $ticket->reasons()->where('ticket_id', $ticket->id)->first();

        return view(
            'layouts.staff.approver.ticket.view_ticket',
            compact([
                'ticket',
                'latestClarification',
                'reason'
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
        DB::commit();

        return back()->with('success', 'Ticket is rejected.');
    }

    public function ticketDetialsApproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::APPROVED,
            'approval_status' => ApprovalStatus::APPROVED
        ]);

        return to_route('approver.ticket.view_ticket_details', [$ticket->id])
            ->with('success', 'The ticket has been approved.');
    }

    public function ticketDetialsDisapproveTicket(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'disapproveTicket')->withInput();

        try {
            DB::transaction(function () use ($request, $ticket) {
                $reason = Reason::create([
                    'ticket_id' => $ticket->id,
                    'description' => $request->input('description')
                ]);

                $reason->ticket()->where('id', $ticket->id)
                    ->update([
                        'status_id' => Status::CLOSED,
                        'approval_status' => ApprovalStatus::DISAPPROVED
                    ]);
            });

            return to_route('approver.ticket.view_ticket_details', [$ticket->id])
                ->with('success', 'The ticket has been disapproved.');

        } catch (\Exception $e) {
            return to_route('approver.ticket.view_ticket_details', [$ticket->id])
                ->with('error', 'Faild to disapprove the ticket. Please try again.');
        }
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

        try {
            DB::transaction(function () use ($request, $ticket) {
                $ticket->update(['status_id' => Status::ON_PROCESS]);

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
            });

            return to_route('approver.ticket.view_ticket_details', [$ticket->id])
                ->with('success', 'The message has been successfully sent.');

        } catch (\Exception $e) {
            return to_route('approver.ticket.view_ticket_details', [$ticket->id])
                ->with('error', 'Faild to send ticket clarification. Please try again.');
        }
    }
}