<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadCloseStatusButtonHeader extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketStatusButtonHeader' => 'render'];

    public function render()
    {
        return view('livewire.staff.ticket.load-close-status-button-header');
    }
}