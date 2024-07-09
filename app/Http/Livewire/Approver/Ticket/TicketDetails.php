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
    public bool $isSlaApproved = false;

    protected $listeners = ['loadTicketDetails' => 'mount'];

    public function mount()
    {
        $this->isSlaApproved = $this->isSlaApproved($this->ticket);
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-details');
    }
}