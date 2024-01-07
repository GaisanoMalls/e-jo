<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\BasicModelQueries;
use App\Models\Ticket;
use Exception;
use Livewire\Component;

class AssignTag extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $selectedTags = [];

    protected $listeners = ['loadAssignTicketForm' => '$refresh'];

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
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-tag', [
            'tags' => $this->queryTags(),
        ]);
    }
}