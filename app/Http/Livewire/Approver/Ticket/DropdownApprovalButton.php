<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Traits\TicketApprovalLevel;
use App\Models\Ticket;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    use TicketApprovalLevel;

    public Ticket $ticket;
    public bool $isApproverIsInConfiguration = false;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        $this->isApproverIsInConfiguration = $this->isApproverIsInConfiguration($this->ticket);
    }

    public function render()
    {
        return view('livewire.approver.ticket.dropdown-approval-button');
    }
}
