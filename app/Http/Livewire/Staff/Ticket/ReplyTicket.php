<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\StaffReplyTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    private function actionOnSubmit()
    {
        $this->replyFiles = null;
        $this->upload++;
        $this->reset('description');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketReplies');
        $this->emit('loadDiscussionCount');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
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

                ActivityLog::make($this->ticket->id, "replied to {$this->ticket->user->profile->getFullName()}");
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