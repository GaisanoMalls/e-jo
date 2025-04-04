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

    /**
     * Determines if the currently logged-in user is allowed to approve the ticket.
     *
     * This function checks two conditions to determine if the user has the necessary permissions to approve the ticket:
     * 1. Ensures that all prior levels of the ticket have been approved by calling `isPriorLevelApproved`.
     * 2. Verifies that the user is configured as an approver for the current ticket by calling `isApproverIsInConfiguration`.
     *
     * @return bool Returns true if the user is allowed to approve the ticket, otherwise false.
     */
    private function isAllowedToApprove()
    {
        return $this->isPriorLevelApproved($this->ticket) && $this->isApproverIsInConfiguration($this->ticket);
    }

    public function render()
    {
        return view('livewire.approver.ticket.dropdown-approval-button');
    }
}
