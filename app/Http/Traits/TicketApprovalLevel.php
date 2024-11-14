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

            if ($ticketApprovals->isNotEmpty()) {
                $requiredApprovals = $ticketApprovals->count();
                $approvedApprovals = $ticketApprovals->where('is_approved', true)->count();

                $approvalCount += $requiredApprovals;
                $approvedCount += $approvedApprovals;

                foreach ($ticketApprovals as $ticketApproval) {
                    if ($ticketApproval && $ticketApproval->helpTopicApprover) {
                        if ($ticketApproval->helpTopicApprover->user_id === auth()->user()->id) {
                            if (!$ticketApproval->is_approved) {
                                $ticketApproval->update(['is_approved' => true]);

                                foreach ($ticketApprovals as $otherTicketApproval) {
                                    if (!$otherTicketApproval->is_approved) {
                                        $otherTicketApproval->update(['is_approved' => true]);
                                        $approvedCount++;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($approvedCount === $approvalCount) {
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