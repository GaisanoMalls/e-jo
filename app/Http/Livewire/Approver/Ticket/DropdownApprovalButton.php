<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Traits\TicketApprovalLevel;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    use TicketApprovalLevel;

    public Ticket $ticket;
    public bool $isAllowedToApprove = false;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        $this->isAllowedToApprove = $this->isAllowedToApprove();
    }

    private function isAllowedToApprove()
    {
        return $this->isPriorLevelApproved($this->ticket) && $this->isApproverIsInConfiguration($this->ticket);
    }

    public function render()
    {
        return view('livewire.approver.ticket.dropdown-approval-button');
    }
}
