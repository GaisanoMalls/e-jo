<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approver\StoreClarificationRequest;
use App\Http\Traits\Approver\Tickets as ApproverTickets;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApproverTicketsController extends Controller
{
    use ApproverTickets, Utils;

    public function openTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.open');
    }

    public function viewedTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.viewed');
    }

    public function approvedTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.approved');
    }

    public function disapprovedTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.disapproved');
    }

    public function onProcessTickets()
    {
        return view('layouts.staff.approver.ticket.statuses.on_process');
    }

    public function viewTicketDetails(Ticket $ticket)
    {
        $latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
            ->orderBy('created_at', 'desc')
            ->first();

        return view(
            'layouts.staff.approver.ticket.view_ticket',
            compact([
                'ticket',
                'latestClarification',
            ])
        );
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
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $requester->user->profile->getFullName();

                ActivityLog::make($ticket->id, $logDescription);
                // Mail::to($ticket->user)->send(new FromApproverClarificationMail($ticket, $request->description));
            });

            return back()->with('success', 'Ticket clarification has been sent.');

        } catch (\Exception $e) {
            return back()->with('error', 'Faild to send the ticket clarification.');
        }
    }
}