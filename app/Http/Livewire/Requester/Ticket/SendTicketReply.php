<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\ReplyTicketRequest;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendTicketReply extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $description, $replyFiles = [], $upload = 0;

    public function rules()
    {
        return (new ReplyTicketRequest())->rules();
    }

    public function messages()
    {
        return (new ReplyTicketRequest())->messages();
    }

    private function actionOnSubmit()
    {
        sleep(1);
        $this->replyFiles = null;
        $this->upload++;
        $this->reset('description');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadDiscussionsCount');
        $this->emit('loadTicketDiscussions');
        $this->emit('loadTicketStatusHeaderText');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
    }

    public function sendTicketReply()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description
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

                $latestReply = Reply::where('ticket_id', $this->ticket->id)
                    ->whereHas('user', fn($user) => $user->where('role_id', '!=', Role::USER))
                    ->latest('created_at')
                    ->first();

                ActivityLog::make($this->ticket->id, 'replied to ' . $latestReply->user->profile->getFullName());
            });

            $this->actionOnSubmit();

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.send-ticket-reply');
    }
}