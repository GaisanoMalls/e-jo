<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\Department;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class TicketController extends Controller
{
    use TicketsByStaffWithSameTemplates;

    public function approvedTickets()
    {
        $approvedTickets = $this->getApprovedTickets();
        return view('layouts.staff.ticket.statuses.approved_tickets', compact('approvedTickets'));
    }

    public function disapprovedTickets()
    {
        $disapprovedTickets = $this->getDisapprovedTickets();
        return view('layouts.staff.ticket.statuses.disapproved_tickets', compact('disapprovedTickets'));
    }

    public function openTickets()
    {
        $openTickets = $this->getOpenTickets();
        return view('layouts.staff.ticket.statuses.open_tickets', compact('openTickets'));
    }

    public function onProcessTickets()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        return view('layouts.staff.ticket.statuses.on_process_tickets', compact('onProcessTickets'));
    }

    public function claimedTickets()
    {
        $claimedTickets = $this->getClaimedTickets();
        return view('layouts.staff.ticket.statuses.claimed_tickets', compact('claimedTickets'));
    }

    public function viewedTickets()
    {
        $viewedTickets = $this->getViewedTickets();
        return view('layouts.staff.ticket.statuses.viewed_tickets', compact('viewedTickets'));
    }

    public function reopenedTickets()
    {
        return view('layouts.staff.ticket.statuses.reopened_tickets');
    }

    public function overdueTickets()
    {
        $overdueTickets = $this->getOverdueTickets();
        return view('layouts.staff.ticket.statuses.overdue_tickets', compact('overdueTickets'));
    }

    public function closedTickets()
    {
        $closedTickets = $this->getClosedTickets();
        return view('layouts.staff.ticket.statuses.closed_tickets', compact('closedTickets'));
    }

    public function viewTicket(Ticket $ticket)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();

        $latestReply = Reply::where('ticket_id', $ticket->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view(
            'layouts.staff.ticket.view_ticket',
            compact([
                'ticket',
                'departments',
                'serviceDepartments',
                'latestReply'
            ])
        );
    }

    public function replyTicket(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required'],
            'replyFiles.*' => [
                'nullable',
                File::types(['jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv'])
                    ->min(1024)
                    ->max(25 * 1024) //25600 (25 MB)
            ]
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTicketReply')->withInput();

        try {
            DB::transaction(function () use ($request, $ticket) {
                $ticket->update(['status_id' => Status::ON_PROCESS]);

                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $ticket->id,
                    'description' => $request->description
                ]);

                if ($request->hasFile('replyFiles')) {
                    foreach ($request->file('replyFiles') as $uploadedReplyFile) {
                        $fileName = $uploadedReplyFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs('public/ticket/reply/files', $uploadedReplyFile, $fileName);

                        $replyFile = new ReplyFile();
                        $replyFile->file_attachment = $fileAttachment;
                        $replyFile->reply_id = $reply->id;

                        $reply->fileAttachments()->save($replyFile);
                    }
                }
            });

            return to_route('staff.ticket.view_ticket', $ticket->id)->with('success', 'Your reply has been sent successfully.');

        } catch (\Exception $e) {
            return to_route('staff.ticket.view_ticket', $ticket->id)->with('error', 'Faild to send a reply for this ticket. Please try again.');
        }
    }

    public function ticketActionGetDepartmentServiceDepartments(Department $department)
    {
        return response()->json($department->teams);
    }
}