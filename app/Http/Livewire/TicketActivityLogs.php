<?php

namespace App\Http\Livewire;

use App\Models\ActivityLog;
use App\Models\Ticket;
use Livewire\Component;

class TicketActivityLogs extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketActivityLogs' => 'fetchTicketActivityLogs'];

    public function fetchTicketActivityLogs()
    {
        $this->ticket->activityLogs;
    }

    public function render()
    {
        return view('livewire.ticket-activity-logs');
    }
}