<?php

namespace App\Http\Livewire\Staff;

use App\Models\Reply;
use App\Models\ReplyLike;
use App\Models\Ticket;
use Livewire\Component;

class TicketReplies extends Component
{
    public Ticket $ticket;
    public $replies = null;

    protected $listeners = ['loadTicketReplies' => 'loadReplies'];

    public function loadReplies()
    {
        $this->replies = $this->ticket->replies;
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

    public function render()
    {
        return view('livewire.staff.ticket-replies');
    }
}