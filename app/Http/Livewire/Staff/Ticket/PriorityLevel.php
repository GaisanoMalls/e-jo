<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class PriorityLevel extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadPriorityLevel' => 'fetchPriorityLevel'];

    public function fetchPriorityLevel()
    {
        $this->ticket->priorityLevel->name;
    }

    public function render()
    {
        return view('livewire.staff.ticket.priority-level');
    }
}