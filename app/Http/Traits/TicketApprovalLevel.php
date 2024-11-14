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
        return $ticket->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
            $approver->where('user_id', auth()->user()->id);
        })->exists();
    }

    protected function ticketHasMoreThanOneApprover(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval);
        })->get()->count() > 1;
    }

    // Function to check if all required approvals are granted
    private function updateTicketStatus(Ticket $ticket)
    {
        $approvalLevels = [1, 2, 3, 4, 5];

        $approvedCount = 0;
        $approvalCount = 0;

        foreach ($approvalLevels as $level) {
            $ticketApprovals = TicketApproval::where('ticket_id', $ticket->id)
                ->withWhereHas('helpTopicApprover', function ($query) use ($level, $ticket) {
                    $query->where([
                        ['help_topic_id', $ticket->helpTopic->id],
                        ['level', $level]
                    ]);
                })
                ->get();

            // If there are approvals for this level, count them
            if ($ticketApprovals->isNotEmpty()) {
                // Count required approvals for this level
                $requiredApprovals = $ticketApprovals->count();

                // Count how many approvals are already marked as approved
                $approvedApprovals = $ticketApprovals->where('is_approved', true)->count();

                // Accumulate the counts for this level
                $approvalCount += $requiredApprovals;
                $approvedCount += $approvedApprovals;

                // Handle approval for individual user at this level
                foreach ($ticketApprovals as $ticketApproval) {
                    // If the ticket approval exists and the current user is the approver
                    if ($ticketApproval->helpTopicApprover->user_id === auth()->user()->id) {
                        // If the ticket approval is not yet approved, approve it
                        if (!$ticketApproval->is_approved) {
                            $ticketApproval->update(['is_approved' => true]);

                            // After this approver approves, mark all other approvers at the same level as approved
                            foreach ($ticketApprovals as $otherTicketApproval) {
                                // If another approver hasn't approved yet, mark them as approved too
                                if (!$otherTicketApproval->is_approved) {
                                    $otherTicketApproval->update(['is_approved' => true]);
                                    $approvedCount++;  // Increment approved count when marking as approved
                                    $approvalCount += $requiredApprovals;
                                }
                            }
                        }
                    }
                }
            }

            // After the loop, check if all approvals are completed
            if ($approvedCount === $approvalCount) {
                // Update ticket status to approved
                $ticket->update([
                    'status_id' => Status::APPROVED,
                    'approval_status' => ApprovalStatusEnum::APPROVED,
                    'svcdept_date_approved' => Carbon::now(),
                ]);
            }
        }
    }

    private function approveLevel1(Ticket $ticket)
    {
        $ticketApproval = TicketApproval::where('ticket_id', $ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver, $ticket) {
                $approver->where([
                    ['help_topic_id', $ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 1);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus($ticket);
        }
    }

    private function approveLevel2(Ticket $ticket)
    {
        $ticketApproval = TicketApproval::where('ticket_id', $ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver, $ticket) {
                $approver->where([
                    ['help_topic_id', $ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 2);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus($ticket);
        }
    }

    private function approveLevel3(Ticket $ticket)
    {
        $ticketApproval = TicketApproval::where('ticket_id', $ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver, $ticket) {
                $approver->where([
                    ['help_topic_id', $ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 3);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus($ticket);
        }
    }

    private function approveLevel4(Ticket $ticket)
    {
        $ticketApproval = TicketApproval::where('ticket_id', $ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver, $ticket) {
                $approver->where([
                    ['help_topic_id', $ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 4);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus($ticket);
        }
    }

    private function approvalLevel5(Ticket $ticket)
    {
        $ticketApproval = TicketApproval::where('ticket_id', $ticket->id)
            ->withWhereHas('helpTopicApprover', function ($approver, $ticket) {
                $approver->where([
                    ['help_topic_id', $ticket->helpTopic->id],
                    ['user_id', auth()->user()->id],
                ])->where('level', 5);
            })->first();

        if ($ticketApproval) {
            $ticketApproval->is_approved = true;  // Mark the approval as 'approved'
            $ticketApproval->save();

            // After approving, check if the ticket should be marked as approved
            $this->updateTicketStatus($ticket);
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