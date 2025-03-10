<?php

namespace App\Http\Livewire\TicketNotif;

use App\Models\Clarification;
use App\Models\Ticket;
use Livewire\Component;
use Route;

class NewClarificationIcon extends Component
{
    public Ticket $ticket;
    public bool $ticketHasNewClarification = false;

    protected $listeners = ['loadNewClarificationIcon' => '$refresh'];

    private function routeByUserRole()
    {
        $currentUser = auth()->user();
        if ($currentUser->isSystemAdmin() || $currentUser->isServiceDepartmentAdmin() || $currentUser->isAgent()) {
            return 'staff.ticket.ticket_clarifications';
        }

        if ($currentUser->isUser()) {
            return 'user.ticket.ticket_clarifications';
        }
    }

    public function render()
    {
        $unviewedClarification = Clarification::where([
            ['user_id', '!=', auth()->user()->id],
            ['ticket_id', $this->ticket->id],
            ['is_viewed', false]
        ])
            ->orderByDesc('created_at')
            ->first();

        if (isset($unviewedClarification) && Route::is($this->routeByUserRole())) {
            $unviewedClarification->update(['is_viewed' => true]);
        } else {
            $this->ticketHasNewClarification = $unviewedClarification && $unviewedClarification->user_id != auth()->user()->id;
        }

        return view('livewire.ticket-notif.new-clarification-icon');
    }
}
