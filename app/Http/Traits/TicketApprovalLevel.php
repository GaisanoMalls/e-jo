<?php

namespace App\Http\Traits;

use App\Models\Ticket;
use App\Models\TicketApproval;

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

    protected function canApproveLevel1(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 1],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && (!$this->level1IsApproved($ticket)
                && !$this->level2IsApproved($ticket)
                && !$this->level3IsApproved($ticket)
                && !$this->level4IsApproved($ticket)
                && !$this->level5IsApproved($ticket));
    }

    protected function canApproveLevel2(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 2],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved($ticket)
                && !$this->level2IsApproved($ticket)
                && !$this->level3IsApproved($ticket)
                && !$this->level4IsApproved($ticket)
                && !$this->level5IsApproved($ticket));
    }

    protected function canApproveLevel3(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 3],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved($ticket)
                && $this->level2IsApproved($ticket)
                && !$this->level3IsApproved($ticket)
                && !$this->level4IsApproved($ticket)
                && !$this->level5IsApproved($ticket));
    }

    protected function canApproveLevel4(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 4],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved($ticket)
                && $this->level2IsApproved($ticket)
                && $this->level3IsApproved($ticket)
                && !$this->level4IsApproved($ticket)
                && !$this->level5IsApproved($ticket));
    }

    protected function canApproveLevel5(Ticket $ticket)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', false]
        ])->withWhereHas('helpTopicApprover', function ($approver) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where([
                    ['level', 5],
                    ['user_id', auth()->user()->id]
                ]);
        })->exists()
            && ($this->level1IsApproved($ticket)
                && $this->level2IsApproved($ticket)
                && $this->level3IsApproved($ticket)
                && $this->level4IsApproved($ticket)
                && !$this->level5IsApproved($ticket));
    }

    protected function isApprovedForLevel(Ticket $ticket, int $level)
    {
        return TicketApproval::where([
            ['ticket_id', $ticket->id],
            ['is_approved', true]
        ])->withWhereHas('helpTopicApprover', function ($approver) use ($level) {
            $approver->whereIn('level', $this->levelsOfApproval)
                ->where('level', $level);
        })->exists();
    }

    protected function level1IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 1);
    }

    protected function level2IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 2);
    }

    protected function level3IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 3);
    }

    protected function level4IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 4);
    }

    protected function level5IsApproved(Ticket $ticket)
    {
        return $this->isApprovedForLevel($ticket, 5);
    }
}