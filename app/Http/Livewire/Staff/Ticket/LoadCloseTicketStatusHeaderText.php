<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class LoadCloseTicketStatusHeaderText extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadTicketStatusTextHeader' => 'render'];

    public function render()
    {
        return view('livewire.staff.ticket.load-close-ticket-status-header-text');
    }
}