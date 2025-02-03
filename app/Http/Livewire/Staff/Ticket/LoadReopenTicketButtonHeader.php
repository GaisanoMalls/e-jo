<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\Status;
use App\Models\Ticket;
use Exception;
use Livewire\Component;

class LoadReopenTicketButtonHeader extends Component
{
    public Ticket $ticket;

    public function render()
    {
        return view('livewire.staff.ticket.load-reopen-ticket-button-header');
    }
}
