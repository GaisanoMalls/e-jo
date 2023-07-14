<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ApprovalStatus;
use App\Models\Department;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function openTickets()
    {
        $openTickets = Ticket::where('status_id', Status::OPEN)
                             ->where('approval_status', ApprovalStatus::APPROVED)
                             ->orderBy('created_at', 'desc')
                             ->get();
        return view('layouts.staff.ticket.statuses.open_tickets', compact('openTickets'));
    }

    public function onProcessTickets()
    {
        $onProcessTickets = Ticket::where('status_id', Status::ON_PROCESS)
                                  ->where('approval_status', ApprovalStatus::APPROVED)
                                  ->orderBy('updated_at', 'desc')
                                  ->get();
        return view('layouts.staff.ticket.statuses.on_process_tickets', compact('onProcessTickets'));
    }

    public function approvedTickets()
    {
        $approvedTickets = Ticket::where('approval_status', ApprovalStatus::APPROVED)
                                  ->orderBy('updated_at', 'desc')
                                  ->get();
        return view('layouts.staff.ticket.statuses.approved_tickets', compact('approvedTickets'));
    }

    public function viewTicket($ticketStatusSlug, $ticketId)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();
        $ticket = Ticket::whereHas('status', function ($query) use ($ticketStatusSlug) {
            $query->where('slug', $ticketStatusSlug);
        })->where('id', $ticketId)->first();

        return view('layouts.staff.ticket.view_ticket',
            compact([
                'ticket',
                'departments',
                'serviceDepartments'
            ])
        );
    }

    public function replyTicket(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required'],
            'replyFiles.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,docx', 'max:30000']
        ]);

        if ($validator->fails()) return back()->withErrors($validator, 'storeTicketReply')->withInput();

        $ticket->update(['status_id' => Status::ON_PROCESS]);

        $reply = Reply::create([
            'ticket_id' => (int) $ticket->id,
            'user_id' => (int) auth()->user()->id,
            'description' => $request->input('description')
        ]);

        if ($request->hasFile('replyFiles')) {
            foreach ($request->file('replyFiles') as $uploadedReplyFile) {
                $fileName = $uploadedReplyFile->getClientOriginalName();
                $fileAttachment = $uploadedReplyFile->storeAs('public/ticket/reply/files', $fileName);

                $replyFile = new ReplyFile();
                $replyFile->file_attachment = $fileAttachment;
                $replyFile->reply_id = $reply->id;

                $reply->fileAttachments()->save($replyFile);
            }
        }

        return to_route('staff.tickets.view_ticket', [$ticket->status->slug, $ticket->id])->with('success', 'Your reply has been sent successfully.');
    }
}
