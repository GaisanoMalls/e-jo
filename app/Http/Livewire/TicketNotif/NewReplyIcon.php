<?php

namespace App\Http\Livewire\TicketNotif;

use App\Models\Reply;
use App\Models\Ticket;
use Livewire\Component;
use Route;

class NewReplyIcon extends Component
{
    public Ticket $ticket;
    public bool $ticketHasNewReply = false;

    protected $listeners = ['loadNewReplyIcon' => '$refresh'];

    private function routeByUserRole()
    {
        $currentUser = auth()->user();
        if ($currentUser->isSystemAdmin() || $currentUser->isServiceDepartmentAdmin() || $currentUser->isAgent()) {
            return 'staff.ticket.view_ticket';
        }

        if ($currentUser->isUser()) {
            return 'user.ticket.view_ticket';
        }
    }

    public function render()
    {
        $unviewedReply = Reply::where([
            ['user_id', '!=', auth()->user()->id],
            ['ticket_id', $this->ticket->id],
            ['is_viewed', false]
        ])
            ->orderByDesc('created_at')
            ->first();

        if (isset($unviewedReply) && Route::is($this->routeByUserRole())) {
            $unviewedReply->update(['is_viewed' => true]);
        } else {
            $this->ticketHasNewReply = $unviewedReply && $unviewedReply->user_id != auth()->user()->id;
        }

        return view('livewire.ticket-notif.new-reply-icon');
    }
}
