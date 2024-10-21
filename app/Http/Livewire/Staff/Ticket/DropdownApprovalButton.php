<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Models\HelpTopicApprover;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Livewire\Component;

class DropdownApprovalButton extends Component
{
    public Ticket $ticket;
    public bool $isApproverIsInConfiguration = false;

    protected $listeners = ['loadDropdownApprovalButton' => '$refresh'];

    public function mount()
    {
        $this->isApproverIsInConfiguration = $this->isApproverIsInConfiguration();
        $this->canApproveToAssignedLevel();
    }

    private function isApproverIsInConfiguration()
    {
        return $this->ticket->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
            $approver->where('user_id', auth()->user()->id);
        })->exists();
    }

    private function canApproveToAssignedLevel()
    {
        $approvalLevels = [1, 2, 3, 4, 5];
        $ticketApprovals = TicketApproval::where('ticket_id', $this->ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver) use ($approvalLevels) {
                $approver->whereIn('level', $approvalLevels);
            })->get();

        dump($ticketApprovals);
        // foreach ($this->approvalLevels as $level) {
        //     foreach ($ticketApprovals as $ticketApproval) {

        //     }
        // }
        // dump(HelpTopicApprover::with('ticketApprovals')->get());
    }

    public function render()
    {
        return view('livewire.staff.ticket.dropdown-approval-button');
    }
}