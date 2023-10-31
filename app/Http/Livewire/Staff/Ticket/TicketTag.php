<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Tag;
use App\Models\Ticket;
use Livewire\Component;

class TicketTag extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketTags' => 'fetchTicketTags'];

    public function fetchTicketTags(): void
    {
        $this->ticket->tags;
    }

    public function clearTags(): void
    {
        $this->ticket->tags()->detach();
        $this->emit('loadTicketTags');
        $this->dispatchBrowserEvent('clear-tag-select-option');
    }

    public function getTagIds(): array
    {
        return Tag::whereHas('tickets', fn($ticket) => $ticket->where('tickets.id', $this->ticket->id))->pluck('id')->toArray();
    }

    public function removeTag($tagId): void
    {
        $this->ticket->tags()->detach($tagId);
        $this->emit('loadTicketTags');

        //  Update the selected tags after removing of tags.
        $this->dispatchBrowserEvent('update-tag-select-option', ['tagIds' => $this->getTagIds()]);
    }

    public function getCurrentAssignedTags(): void
    {
        $this->dispatchBrowserEvent('get-current-assigned-tags', ['tagIds' => $this->getTagIds()]);
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-tag');
    }
}
