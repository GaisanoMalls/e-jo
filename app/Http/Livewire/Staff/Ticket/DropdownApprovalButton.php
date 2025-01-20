<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\TicketApprovalLevel;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function render()
    {
        return view('livewire.staff.ticket.dropdown-approval-button');
    }
}