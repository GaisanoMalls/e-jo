<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\TicketApprovalLevel;
use App\Models\NonConfigApprover;
use App\Models\Ticket;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    use TicketApprovalLevel;

    public Ticket $ticket;
    public bool $isApproverIsInConfiguration = false;
    public bool $isApproverIsInNonConfigApproval = false;
    public bool $hasNonConfigApproval = false;
    public bool $nonConfigApprovalIsApproved = false;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        $this->isApproverIsInConfiguration = $this->isApproverIsInConfiguration($this->ticket);
        $this->hasNonConfigApproval = $this->ticket->nonConfigApprover()->exists();
        $this->nonConfigApprovalIsApproved = data_get($this->ticket->nonConfigApprover, 'approvers.is_approved', false);
        $this->isApproverIsInNonConfigApproval = $this->ticket->nonConfigApprover()->whereJsonContains('approvers->id', auth()->user()->id)->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.dropdown-approval-button');
    }
}