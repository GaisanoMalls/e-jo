<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Ticket;
use Exception;
use Livewire\Component;

class AssignTag extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $selectedTags = [];
    public $tags = [];

    protected $listeners = ['loadAssignTicketForm' => '$refresh'];

    public function mount()
    {
        $this->tags = $this->queryTags();
    }

    /**
     * Assign ticket tags
     */
    public function saveAssignTicketTag()
    {
        try {
            $this->ticket->tags()->sync($this->selectedTags);
            $this->emit('loadTicketTags');
            $this->dispatchBrowserEvent('close-modal');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-tag');
    }
}