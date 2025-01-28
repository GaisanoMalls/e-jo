<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\StaffReplyTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Staff\StaffReplyMail;
use App\Models\ActivityLog;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Status;
use App\Models\Ticket;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mail;

class ReplyTicket extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $upload = 0;
    public $description;
    public $replyFiles = [];

    public function rules()
    {
        return (new StaffReplyTicketRequest())->rules();
    }

    public function messages()
    {
        return (new StaffReplyTicketRequest())->messages();
    }

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadTicketReplies',
            'loadDiscussionCount',
            'loadBackButtonHeader',
            'loadTicketStatusTextHeader',
            'loadSidebarCollapseTicketStatus',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->replyFiles = [];
        $this->upload++;
        $this->triggerEvents();
        $this->reset('description');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
    }

    public function replyTicket()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description,
                ]);

                if ($this->replyFiles) {
                    collect($this->replyFiles)->each(function ($uploadedReplyFile) use ($reply) {
                        $fileName = $uploadedReplyFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(),
                            $uploadedReplyFile,
                            $fileName
                        );
                        $reply->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    });
                }

                $requester = $this->ticket->user()->with('profile')->withTrashed()->first();
                Notification::send(
                    $requester,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Replied)",
                        message: auth()->user()->profile->getFullName . " replied to your message"
                    )
                );
                Mail::to($requester)->send(new StaffReplyMail($this->ticket, $requester, $this->description));
                ActivityLog::make(
                    ticket_id: $this->ticket->id,
                    description: "replied to {$requester->profile->getFullName}"
                );
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.reply-ticket');
    }
}