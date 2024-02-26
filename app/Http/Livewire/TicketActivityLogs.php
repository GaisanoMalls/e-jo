<?php

namespace App\Http\Livewire;

use App\Models\ActivityLog;
use App\Models\Ticket;
use Livewire\Component;

class TicketActivityLogs extends Component
{
    public Ticket $ticket;
    public bool $isAll = false;
    public bool $isMyLogsOnly = false;
    public $ticketLogs = [];

    protected $listeners = ['loadTicketLogs' => 'loadLogs'];

    public function filterAll()
    {
        $this->isAll = true;
        $this->isMyLogsOnly = false;
        $this->loadLogs();
    }

    public function filterMyLogs()
    {
        if (!$this->isMyLogsOnly || $this->hasMyLogs()) {
            $this->isMyLogsOnly = true;
            $this->isAll = false;
            $this->loadLogs();
        }
    }

    public function hasMyLogs()
    {
        // Check if there are logs for the current user
        return ActivityLog::where('ticket_id', $this->ticket->id)
            ->where('user_id', auth()->user()->id)
            ->exists();
    }

    public function loadLogs()
    {
        if ($this->isAll) {
            $this->ticketLogs = $this->ticket->activityLogs;
        }

        if ($this->isMyLogsOnly) {
            $this->ticketLogs = ActivityLog::where('ticket_id', $this->ticket->id)
                ->where('user_id', auth()->user()->id)->orderByDesc('created_at')
                ->get();
        }
    }

    public function render()
    {
        if (!$this->isAll && !$this->isMyLogsOnly) {
            $this->filterAll();
        }

        return view('livewire.ticket-activity-logs');
    }
}