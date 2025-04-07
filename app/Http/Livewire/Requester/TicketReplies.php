<?php

namespace App\Http\Livewire\Requester;

use App\Models\Ticket;
use Livewire\Component;

class TicketReplies extends Component
{
    public Ticket $ticket;
    public $replies = null;

    protected $listeners = ['loadTicketDiscussions' => '$refresh'];

    /**
     * Loads all replies associated with the current ticket.
     *
     * This method retrieves the replies related to the ticket instance
     * and assigns them to the $replies property of the component.
     *
     * @return void
     */
    public function loadReplies()
    {
        // Retrieve and assign the replies of the current ticket to the $replies property.
        $this->replies = $this->ticket->replies;
    }

    /**
     * Emits an event to notify other components to load the latest reply.
     *
     * This method broadcasts the 'loadLatestReply' event, which can be
     * listened to by other Livewire components to fetch and display the latest reply.
     *
     * @return void
     */
    public function getLatestReply()
    {
        // Emit the 'loadLatestReply' event to update the latest reply.
        $this->emit('loadLatestReply');
    }

    public function render()
    {
        return view('livewire.requester.ticket-replies');
    }
}