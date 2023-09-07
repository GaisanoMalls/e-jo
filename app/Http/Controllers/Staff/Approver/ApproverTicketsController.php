<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approver\StoreClarificationRequest;
use App\Http\Requests\Approver\StoreDisapproveTicketRequest;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Reason;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApproverTicketsController extends Controller
{
    use ApproverTickets, Utils;

    public function ticketStatusToViewed(Ticket $ticket)
    {
        $ticket->update(['status_id' => Status::VIEWED]);
        ActivityLog::make($ticket->id, 'seen the ticket');
    }

    public function openTickets()
    {
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
        $latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
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

        ActivityLog::make($ticket->id, 'approved the ticket');

        return back()->with('success', 'Ticket was successfully approved.');
    }

    public function disapproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::CLOSED,
            'approval_status' => ApprovalStatus::DISAPPROVED
        ]);

        ActivityLog::make($ticket->id, 'disapproved the ticket');

        return back()->with('success', 'Ticket is rejected.');
    }

    public function ticketDetialsApproveTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::APPROVED,
            'approval_status' => ApprovalStatus::APPROVED
        ]);

        ActivityLog::make($ticket->id, 'approved the ticket');

        return back()->with('success', 'The ticket has been approved.');
    }

    public function ticketDetialsDisapproveTicket(StoreDisapproveTicketRequest $request, Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($request, $ticket) {
                $reason = Reason::create([
                    'ticket_id' => $ticket->id,
                    'description' => $request->description
                ]);

                $reason->ticket()->where('id', $ticket->id)
                    ->update([
                        'status_id' => Status::CLOSED,
                        'approval_status' => ApprovalStatus::DISAPPROVED
                    ]);

                ActivityLog::make($ticket->id, 'disapproved the ticket');
            });

            return back()->with('success', 'The ticket has been disapproved.');

        } catch (\Exception $e) {
            return back()->with('error', 'Faild to disapprove the ticket. Please try again.');
        }
    }

    // * Clarifications
    public function sendClarification(StoreClarificationRequest $request, Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($request, $ticket) {
                $ticket->update(['status_id' => Status::ON_PROCESS]);

                $clarification = Clarification::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $ticket->id,
                    'description' => $request->description
                ]);

                if ($request->hasFile('clarificationFiles')) {
                    foreach ($request->file('clarificationFiles') as $uploadedClarificationFile) {
                        $fileName = $uploadedClarificationFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/clarification_attachments/" . $this->fileDirByUserType(), $uploadedClarificationFile, $fileName);

                        $clarificationFile = new ClarificationFile();
                        $clarificationFile->file_attachment = $fileAttachment;
                        $clarificationFile->clarification_id = $clarification->id;

                        $clarification->fileAttachments()->save($clarificationFile);
                    }
                }

                // * GET THE REQUESTER
                $requester = $clarification->whereHas('user', function ($user) {
                    $user->where('id', '!=', auth()->user()->id);
                })
                    ->where('ticket_id', $ticket->id)
                    ->latest('created_at')
                    ->first();

                // * CONSTRUCT A LOG DESCRIPTION
                $logDescription = $ticket->clarifications()->where('user_id', '!=', auth()->user()->id)->count() == 0
                    ? 'sent a clarrification'
                    : 'replied a clarification to ' . $requester->user->profile->getFullName();

                ActivityLog::make($ticket->id, $logDescription);
            });

            return back()->with('success', 'The message has been successfully sent.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Faild to send ticket clarification. Please try again.');
        }
    }
}