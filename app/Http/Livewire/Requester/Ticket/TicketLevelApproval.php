<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class TicketLevelApproval extends Component
{
    public Ticket $ticket;
    public Collection $approvers;
    public Collection $ticketApprovals;
    public array $approvalLevels = [1, 2, 3, 4, 5];

    public function mount()
    {
        $this->ticketApprovals = TicketApproval::with('helpTopicApprover')
            ->withWhereHas('ticket', fn($ticket) => $ticket->where('id', $this->ticket->id))
            ->get();
    }

    /**
     * Retrieves approvers for a specific approval level based on ticket context.
     *
     * Fetches users who are authorized to approve tickets:
     * - At the specified approval level
     * - For the ticket's help topic
     * - Within the ticket requester's departments and branches
     *
     * Includes user profiles and nested approval configuration relationships.
     *
     * @param int $level The approval level to filter by (e.g., 1 for first-level)
     * @return \Illuminate\Database\Eloquent\Collection Returns collection of:
     *         - User models with profile data
     *         - Filtered by approval configuration
     *         - Sorted by database default order
     */
    public function fetchApprovers(int $level)
    {
        return User::with('profile') // Eager load user profiles
            ->withWhereHas('helpTopicApprovals', function ($query) use ($level) {
                $query->where('level', $level) // Filter by approval level
                    ->withWhereHas('configuration', function ($config) {
                        // Load nested approval configuration with:
                        $config->with('approvers') // Approvers relationship
                            ->where('help_topic_id', $this->ticket->help_topic_id) // Matching help topic 
                            ->whereIn('bu_department_id', $this->ticket->user?->buDepartments->pluck('id'));  // User's departments  
                    });
            })->get(); // Execute query and return results
    }

    public function isApprovalApproved()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true]
        ])
            ->withWhereHas('helpTopicApprover', fn($approver) =>
                $approver->where('help_topic_id', $this->ticket->help_topic_id))
            ->exists();
    }

    public function islevelApproved(int $level)
    {
        return TicketApproval::where('is_approved', true)
            ->withWhereHas('helpTopicApprover', fn($approver) =>
                $approver->where('level', $level)
                    ->where('help_topic_id', $this->ticket->help_topic_id))
            ->withWhereHas('ticket', fn($ticket) => $ticket->where('id', $this->ticket->id))
            ->exists();
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-level-approval');
    }
}
