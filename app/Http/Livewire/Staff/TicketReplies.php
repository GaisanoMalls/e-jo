<?php

namespace App\Http\Livewire\Staff;

use App\Http\Requests\StaffQouteReplyRequest;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\QouteReply;
use App\Models\Reply;
use App\Models\ReplyFile;
use App\Models\ReplyLike;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketReplies extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $replies = null;

    protected $listeners = ['loadTicketReplies' => 'loadReplies'];
    public $upload = 0;
    public $quoteReplyDescription = '';
    public $quoteReplyFiles = [];
    public $qouteReplyMessage = '';
    public $qouteReplyId = null;

    public function loadReplies()
    {
        $this->replies = $this->ticket->replies;
    }

    public function rules()
    {
        return (new StaffQouteReplyRequest())->rules();
    }

    public function messages()
    {
        return (new StaffQouteReplyRequest())->messages();
    }

    public function likeReply(Reply $reply)
    {
        ReplyLike::create([
            'reply_id' => $reply->id,
            'liked_by' => auth()->user()->id
        ]);

        $this->loadReplies();
    }

    public function unlikeReply(Reply $reply)
    {
        ReplyLike::where([
            ['reply_id', $reply->id],
            ['liked_by', auth()->user()->id]
        ])->delete();

        $this->loadReplies();
    }

    public function isLiked(Reply $reply)
    {
        return ReplyLike::where('reply_id', $reply->id)->where('liked_by', auth()->user()->id)->exists();
    }

    public function qouteReply(Reply $reply)
    {
        $this->qouteReplyMessage = $reply->description;
        $this->qouteReplyId = $reply->id;
    }

    private function actionOnSubmit()
    {
        $this->quoteReplyFiles = [];
        $this->upload++;
        $this->reset('qouteReplyDescription');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function sendQuoteReply()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->quoteReplyDescription,
                ]);

                if ($this->qouteReplyId) {
                    $reply->update(['quoted_reply_id' => $this->qouteReplyId]);
                }

                if ($this->quoteReplyFiles) {
                    foreach ($this->quoteReplyFiles as $uploadedReplyFile) {
                        $fileName = $uploadedReplyFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(),
                            $uploadedReplyFile,
                            $fileName
                        );

                        $replyFile = new ReplyFile();
                        $replyFile->file_attachment = $fileAttachment;
                        $replyFile->reply_id = $reply->id;

                        $reply->fileAttachments()->save($replyFile);
                    }
                }

                ActivityLog::make($this->ticket->id, "replied to {$this->ticket->user->profile->getFullName()}");
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Failed to send ticket clarification. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket-replies');
    }
}