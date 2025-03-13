<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Traits\Utils;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketDetails extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadTicketDetails' => 'mount'];

    public function render()
    {
        return view('livewire.approver.ticket.ticket-details');
    }
}