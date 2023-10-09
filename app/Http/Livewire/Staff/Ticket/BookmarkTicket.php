<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use App\Models\Bookmark;
use Livewire\Component;

class BookmarkTicket extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadBookmarkButton' => '$refresh'];

    public function bookmark()
    {
        sleep(1);
        Bookmark::firstOrCreate([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->user()->id
        ]);
    }

    public function removeBookmark()
    {
        Bookmark::where('ticket_id', $this->ticket->id)
            ->where('user_id', auth()->user()->id)->delete();
    }

    private function isBookmarked()
    {
        return Bookmark::where('ticket_id', $this->ticket->id)
            ->where('user_id', auth()->user()->id)->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.bookmark-ticket', [
            'isBookmarked' => $this->isBookmarked()
        ]);
    }
}