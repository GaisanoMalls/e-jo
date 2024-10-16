<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\Ticket;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    public Ticket $ticket;
    public bool $isApproverIsInConfiguration = false;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        $this->isApproverIsInConfiguration = $this->isApproverIsInConfiguration();
    }

    public function isApproverIsInConfiguration()
    {
        return $this->ticket->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
            $approver->where('user_id', auth()->user()->id);
        })->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.dropdown-approval-button');
    }
}