<?php

namespace App\Http\Traits;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use Illuminate\Support\Carbon;

trait TicketApprovalLevel
{
    private array $levelsOfApproval = [1, 2, 3, 4, 5];

    protected function isApproverIsInConfiguration(Ticket $ticket)
    {
        return TicketApproval::where('ticket_id', $ticket->id)
            ->whereHas('helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->exists();
    }

    // Function to check if all required approvals are granted
    private function updateTicketStatus(Ticket $ticket)
    {
        $approvalLevels = [1, 2, 3, 4, 5];
        $allLevelsApproved = true;

        foreach ($approvalLevels as $level) {
            $ticketApprovals = TicketApproval::where('ticket_id', $ticket->id)
                ->withWhereHas('helpTopicApprover', function ($query) use ($level, $ticket) {
                    $query->where([
                        ['help_topic_id', $ticket->helpTopic->id],
                        ['level', $level]
                    ]);
                })
                ->get();

            if ($ticketApprovals->isNotEmpty()) {
                foreach ($ticketApprovals as $ticketApproval) {
                    if ($ticketApproval && $ticketApproval->helpTopicApprover) {
                        if ($ticketApproval->helpTopicApprover->user_id === auth()->user()->id) {
                            if (!$ticketApproval->is_approved) {
                                $ticketApproval->update(['is_approved' => true]);

                                foreach ($ticketApprovals as $otherTicketApproval) {
                                    if (!$otherTicketApproval->is_approved) {
                                        $otherTicketApproval->update(['is_approved' => true]);
                                    }
                                }
                            }
                        }
                    }
                }

                // Check if all approvals for the current level are complete
                if (
                    !$ticketApprovals->every(function ($approval) {
                        return $approval->is_approved;
                    })
                ) {
                    $allLevelsApproved = false;
                }
            }
        }

        // Update ticket status if all levels are approved
        if ($allLevelsApproved) {
            $ticket->update([
                'status_id' => Status::APPROVED,
                'approval_status' => ApprovalStatusEnum::APPROVED,
                'svcdept_date_approved' => Carbon::now(),
            ]);
        }
    }

    private function isApprovedForLevel(Ticket $ticket, int $level)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', true]
        ])->withWhereHas('helpTopicApprover', function ($approver) use ($level) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where('level', $level);
        })->exists();
    }

    private function level1IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 1);
    }

    private function level2IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 2);
    }

    private function level3IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 3);
    }

    private function level4IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 4);
    }

    private function level5IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 5);
    }
}