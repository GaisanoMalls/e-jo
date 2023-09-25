<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\StaffReplyTicketRequest;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReplyTicket extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $description, $replyFiles = [];

    public function rules()
    {
        return (new StaffReplyTicketRequest())->rules();
    }

    public function messages()
    {
        return (new StaffReplyTicketRequest())->messages();
    }

    public function replyTicket()
    {
        $validatedData = $this->validate();

        DB::transaction(function () use ($validatedData) {
            $this->ticket->update(['status_id' => Status::ON_PROCESS]);

            $reply = Reply::create([
                'user_id' => auth()->user()->id,
                'ticket_id' => $this->ticket->id,
                'description' => $validatedData['description']
            ]);

            if ($this->replyFiles) {
                foreach ($this->replyFiles as $uploadedReplyFile) {
                    $fileName = $uploadedReplyFile->getClientOriginalName();
                    $fileAttachment = Storage::putFileAs("public/ticket/{$this->ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(), $uploadedReplyFile, $fileName);

                    $replyFile = new ReplyFile();
                    $replyFile->file_attachment = $fileAttachment;
                    $replyFile->reply_id = $reply->id;

                    $reply->fileAttachments()->save($replyFile);
                }
            }

            sleep(1);
            $this->emit('loadTicketReplies');
            $this->emit('loadTicketStatusTextHeader');
            $this->dispatchBrowserEvent('close-modal');

            ActivityLog::make($this->ticket->id, "replied to {$this->ticket->user->profile->getFullName()}");
        });
    }

    public function render()
    {
        $latestReply = Reply::where('ticket_id', $this->ticket->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('livewire.staff.ticket.reply-ticket', [
            'latestReply' => $latestReply
        ]);
    }
}