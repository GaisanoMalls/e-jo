<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadCostingButtonHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadCostingButtonHeader' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.ticket.load-costing-button-header');
    }
}
