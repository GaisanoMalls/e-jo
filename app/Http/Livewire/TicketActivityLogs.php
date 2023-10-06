<?php

namespace App\Http\Livewire;

use App\Models\ActivityLog;
use App\Models\Ticket;
use Livewire\Component;

class TicketActivityLogs extends Component
{
    public Ticket $ticket;
    public $isAll = false;
    public $isMyLogsOnly = false;
    public $ticketLogs = [];

    protected $listeners = ['loadTicketActivityLogs' => '$refresh'];

    public function filterAll()
    {
        $this->isAll = true;
        $this->isMyLogsOnly = true;
        $this->dispatchBrowserEvent('display-my-logs-label', ['allLabel' => 'All']);
        $this->emit('loadTicketActivityLogs');
    }

    public function filterMyLogs()
    {
        $this->isMyLogsOnly = true;
        $this->isAll = false;
        $this->ticketLogs = ActivityLog::where('ticket_id', $this->ticket->id)->where('user_id', auth()->user()->id)->get();
    }

    public function render()
    {
        $this->isAll ? $this->ticketLogs = $this->ticket->activityLogs
            : ($this->isMyLogsOnly
                ? $this->filterMyLogs()
                : $this->ticketLogs = $this->ticket->activityLogs);

        return view('livewire.ticket-activity-logs');
    }
}