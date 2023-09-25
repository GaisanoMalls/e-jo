<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use App\Models\Bookmark;
use Livewire\Component;

class BookmarkTicket extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadBookmarkButton' => 'render'];

    public function bookmark()
    {
        Bookmark::firstOrCreate(['ticket_id' => $this->ticket->id]);
        sleep(1);
    }

    public function removeBookmark()
    {
        Bookmark::where('ticket_id', $this->ticket->id)->delete();
    }

    private function isBookmarked()
    {
        return Bookmark::where('ticket_id', $this->ticket->id)->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.bookmark-ticket', [
            'isBookmarked' => $this->isBookmarked()
        ]);
    }
}