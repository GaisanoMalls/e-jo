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
    public bool $isApproverIsInConfiguration = false;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        // dd(TicketApproval::where('ticket_id', $this->ticket->id)
        //     ->withWhereHas('helpTopicApprover', function ($approver) {
        //         $approver->where('user_id', auth()->user()->id);
        //     })->get(), auth()->user()->id);
        $this->isApproverIsInConfiguration = $this->isApproverIsInConfiguration($this->ticket);
    }

    public function render()
    {
        return view('livewire.approver.ticket.dropdown-approval-button');
    }
}
