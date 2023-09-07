<?php

namespace App\Http\Controllers\Staff\ServiceDeptAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceDeptAdmin\StoreClarificationRequest as ServiceDeptAdminClarificationRequest;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketLevel1ApprovalController extends Controller
{
    use Utils;

    public function index()
    {
        $level1ApprovalTickets = Ticket::where(function ($statusQuery) {
            $statusQuery->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::FOR_APPROVAL);
        })->where('head_approval_completed', false)
            ->whereJsonContains('service_department_head_approver', [
                'id' => auth()->user()->id,
                'is_approved' => false
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.staff.service_department_admin.level_1_tickets_approval', compact('level1ApprovalTickets'));
    }

    public function show(Ticket $ticket)
    {
        $latestClarification = Clarification::whereHas('ticket', fn($query) => $query->where('ticket_id', $ticket->id))
            ->whereHas('user', fn($user) => $user->where('user_id', '!=', auth()->user()->id))
            ->orderBy('created_at', 'desc')
            ->first();

        $reason = $ticket->reasons()->where('ticket_id', $ticket->id)->first();

        return view(
            'layouts.staff.service_department_admin.view_level_1_approval_ticket',
            compact([
                'ticket',
                'latestClarification',
                'reason'
            ])
        );
    }

    // * Clarifications
    public function sendClarification(ServiceDeptAdminClarificationRequest $request, Ticket $ticket)
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