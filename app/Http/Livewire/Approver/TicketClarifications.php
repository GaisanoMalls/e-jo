<?php

namespace App\Http\Livewire\Approver;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketClarifications extends Component
{
    public Ticket $ticket;
    public ?Collection $clarifications = null;

    protected $listeners = ['loadClarifications' => '$refresh'];

    /**
     * Loads all clarifications associated with the current ticket.
     * 
     * Populates the $clarifications property with the ticket's clarification records.
     * This is typically used to refresh the clarification data for display purposes.
     *
     * @return void
     */
    public function loadClarifications(): void
    {
        $this->clarifications = $this->ticket->clarifications;
    }

    /**
     * Triggers an event to load the most recent clarification.
     * 
     * Emits a 'loadLatestClarification' event which should be handled
     * by a listener to fetch and display the latest clarification record.
     * This is typically used to refresh the most recent clarification
     * in the UI without reloading all clarification data.
     *
     * @return void
     * @fires loadLatestClarification Event to load the latest clarification
     */

    public function getLatestClarification(): void
    {
        $this->emit('loadLatestClarification');
    }

    public function render()
    {
        return view('livewire.approver.ticket-clarifications');
    }
}
