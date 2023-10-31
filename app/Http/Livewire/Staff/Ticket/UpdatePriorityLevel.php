<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\BasicModelQueries;
use App\Models\ActivityLog;
use App\Models\Ticket;
use Exception;
use Livewire\Component;

class UpdatePriorityLevel extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $priority_level;

    public function mount(): void
    {
        $this->priority_level = $this->ticket->priority_level_id;
    }

    private function actionOnSubmit(): void
    {
        sleep(1);
        $this->emit('loadPriorityLevel');
        $this->emit('loadTicketLogs');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function updatePriorityLevel(): void
    {
        try {
            if ($this->priority_level != $this->ticket->priority_level_id) {
                $currentLevel = $this->ticket->priorityLevel->name;
                $this->ticket->update(['priority_level_id' => $this->priority_level]);
                $this->ticket->refresh();
                $newLevel = $this->ticket->priorityLevel->name;

                ActivityLog::make($this->ticket->id, "changed the priority level from {$currentLevel} to {$newLevel}");
                $this->actionOnSubmit();

            }
        } catch (Exception $e) {
            dd($e->getMessage());
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