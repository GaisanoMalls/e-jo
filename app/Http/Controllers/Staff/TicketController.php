<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Traits\ServiceDepartmentAdmin\Tickets;
use App\Models\ApprovalStatus;
use App\Models\Department;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\ServiceDepartment;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Type\ObjectType;

class TicketController extends Controller
{
    use Tickets;

    public function approvedTickets()
    {
        $approvedTickets = $this->getApprovedTickets();
        return view('layouts.staff.ticket.statuses.approved_tickets', compact('approvedTickets'));
    }

    public function openTickets()
    {
        $openTickets = $this->getOpentTickets();
        return view('layouts.staff.ticket.statuses.open_tickets', compact('openTickets'));
    }

    public function onProcessTickets()
    {
        $onProcessTickets = $this->getOnProcessTickets();
        return view('layouts.staff.ticket.statuses.on_process_tickets', compact('onProcessTickets'));
    }


    public function viewTicket(int $ticketId)
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $serviceDepartments = ServiceDepartment::orderBy('name', 'asc')->get();
        $ticket = Ticket::with('replies')->where('id', $ticketId)->first();

        $latestReply = Reply::where('ticket_id', $ticketId)
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
            'replyFiles.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv', 'max:30000']
        ]);

        if ($validator->fails())
            return back()->withErrors($validator, 'storeTicketReply')->withInput();

        $ticket->update(['status_id' => Status::ON_PROCESS]);

        $reply = Reply::create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
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

        return to_route('staff.ticket.view_ticket', $ticket->id)->with('success', 'Your reply has been sent successfully.');
    }
}