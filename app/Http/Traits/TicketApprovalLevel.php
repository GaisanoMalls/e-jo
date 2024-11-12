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
        $approvalLevels = [1, 2, 3, 4, 5];  // Define the levels of approval you're checking for

        // Initialize counts
        $approvedCount = 0;
        $approvalCount = 0;

        // Loop through each approval level
        foreach ($approvalLevels as $level) {
            // Count how many approvals are required at each level
            $requiredApprovals = TicketApproval::where('ticket_id', $ticket->id)
                ->withWhereHas('helpTopicApprover', function ($approver) use ($level, $ticket) {
                    $approver->where([
                        ['help_topic_id', $ticket->helpTopic->id],
                        ['level', $level]
                    ]);
                })->count();

            // Count how many approvals are already approved at each level
            $approvedApprovals = TicketApproval::where('ticket_id', $ticket->id)
                ->withWhereHas('helpTopicApprover', function ($approver) use ($level, $ticket) {
                    $approver->where([
                        ['help_topic_id', $ticket->helpTopic->id],
                        ['level', $level]
                    ]);
                })
                ->where('is_approved', true)  // Assuming the status is 'approved'
                ->count();

            // Accumulate the counts
            $approvalCount += $requiredApprovals;
            $approvedCount += $approvedApprovals;

            // Handle approval for individual user at this level
            $ticketApproval = TicketApproval::where('ticket_id', $ticket->id)
                ->withWhereHas('helpTopicApprover', function ($approver) use ($level, $ticket) {
                    $approver->where([
                        ['help_topic_id', $ticket->helpTopic->id],
                        ['level', $level],
                        ['user_id', auth()->user()->id],
                    ]);
                })->first();

            dd($ticketApproval);
            // If the ticket approval exists, mark it as approved
            if ($ticketApproval && !$ticketApproval->is_approved) {
                $ticketApproval->update(['is_approved' => true]);
            }
        }

        // After the loop, check if the approval conditions are met
        if ($approvedCount === $approvalCount) {
            $ticket->update([
                'status_id' => Status::APPROVED,
                'approval_status' => ApprovalStatusEnum::APPROVED,
                'svcdept_date_approved' => Carbon::now(),
            ]);
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