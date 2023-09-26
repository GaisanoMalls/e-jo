<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\BasicModelQueries;
use App\Models\Ticket;
use Livewire\Component;

class AssignTag extends Component
{
    use BasicModelQueries;

    public Ticket $ticket;
    public $selectedTags = [];
    protected $listeners = ['loadAssignTicketForm' => 'render'];

    public function saveAssignTicketTag()
    {
        try {
            $this->ticket->tags()->sync($this->selectedTags);

            sleep(1);
            $this->emit('loadTicketTags');
            $this->dispatchBrowserEvent('close-modal');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.assign-tag', [
            'tags' => $this->queryTags()
        ]);
    }
}