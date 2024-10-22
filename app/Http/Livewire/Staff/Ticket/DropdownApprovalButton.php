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
        dump($this->canApproveToAssignedLevel());
    }

    private function isApproverIsInConfiguration()
    {
        return $this->ticket->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
            $approver->where('user_id', auth()->user()->id);
        })->exists();
    }

    private function canApproveToAssignedLevel()
    {
        // Get the highest approved level from helpTopicApprover
        $highestApprovedLevel = TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true]
        ])
            ->withWhereHas('helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->get()
            ->pluck('helpTopicApprover.level')
            ->max();

        // If no levels have been approved yet, set highestApprovedLevel to 0
        if ($highestApprovedLevel === null) {
            $highestApprovedLevel = 0;
        }

        // Fetch all unapproved ticket approvals for the current ticket
        $ticketApprovals = TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', false]
        ])->with('helpTopicApprover')->get();

        // Check if the unapproved levels are sequentially following the highest approved level
        foreach ($ticketApprovals as $ticketApproval) {
            $currentLevel = $ticketApproval->helpTopicApprover->level;

            // Allow approval if the current level is the next in sequence
            if ($currentLevel === ($highestApprovedLevel + 1)) {
                return true; // Can approve the current level
            }
        }

        return false; // No eligible levels to approve
    }

    public function render()
    {
        return view('livewire.staff.ticket.dropdown-approval-button');
    }
}