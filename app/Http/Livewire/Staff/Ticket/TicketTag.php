<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Tag;
use App\Models\Ticket;
use Livewire\Component;

class TicketTag extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketTags' => 'fetchTicketTags'];

    public function fetchTicketTags()
    {
        $this->ticket->tags;
    }

    public function clearTags()
    {
        $this->ticket->tags()->detach();
        $this->emit('loadTicketTags');
        $this->dispatchBrowserEvent('clear-tag-select-option');
    }

    public function removeTag($tagId)
    {
        $this->ticket->tags()->detach($tagId);
        $this->emit('loadTicketTags');

        //  Update the selected tags after removing of tags.
        $this->dispatchBrowserEvent('update-tag-select-option', [
            'tagIds' => Tag::whereHas('tickets', fn($ticket) => $ticket->where('tickets.id', $this->ticket->id))->pluck('id')->toArray()
        ]);
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-tag');
    }
}