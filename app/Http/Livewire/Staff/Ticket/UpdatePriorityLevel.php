<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\BasicModelQueries;
use App\Models\ActivityLog;
use App\Models\Ticket;
use Livewire\Component;

class UpdatePriorityLevel extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $priority_level;

    public function mount()
    {
        $this->priority_level = $this->ticket->priority_level_id;
    }

    public function updatePriorityLevel()
    {
        try {
            if ($this->priority_level != $this->ticket->priority_level_id) {
                $currentLevel = $this->ticket->priorityLevel->name;
                $this->ticket->update(['priority_level_id' => $this->priority_level]);
                $this->ticket->refresh();
                $newLevel = $this->ticket->priorityLevel->name;

                ActivityLog::make($this->ticket->id, "changed the priority level from {$currentLevel} to {$newLevel}");

                $this->emit('loadPriorityLevel');
                $this->emit('loadTicketActivityLogs');
                $this->dispatchBrowserEvent('close-modal');
            }
        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.update-priority-level', [
            'priorityLevels' => $this->queryPriorityLevels(),
        ]);
    }
}