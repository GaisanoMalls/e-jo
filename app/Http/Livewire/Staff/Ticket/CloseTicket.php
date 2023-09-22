<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Status;
use App\Models\Ticket;
use Livewire\Component;

class CloseTicket extends Component
{
    public Ticket $ticket;

    public function closeTicket()
    {
        $this->ticket->update(['status_id' => Status::CLOSED]);
        $this->emit('loadPriorityLevel');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->dispatchBrowserEvent('close-modal');

        sleep(1);
        flash()->addSuccess('Ticket has been closed');
    }

    public function render()
    {
        return view('livewire.staff.ticket.close-ticket');
    }
}