<?php

namespace App\Http\Traits\Requester;

use App\Enums\ApprovalStatusEnum;
use App\Models\Clarification;
use App\Models\Reply;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

trait Tickets
{
    public function getOpenTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])->where([
            ['status_id', Status::OPEN],
            ['status_id', Status::OPEN],
            ['user_id', User::role(Role::USER)->where('id', auth()->user()->id)->value('id')]
        ])->orderByDesc('created_at')->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::ON_PROCESS)
                ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getViewedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)
                ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where([
                ['status_id', Status::APPROVED],
                ['approval_status', ApprovalStatusEnum::APPROVED],
                ['user_id', User::role(Role::USER)->where('id', auth()->user()->id)->value('id')]
            ]))->orHas('ticketCosting')
            ->orderByDesc('created_at')->get();
    }

    public function getClaimedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where([
                ['status_id', Status::CLAIMED],
                ['approval_status', ApprovalStatusEnum::APPROVED]
            ]))->whereNotNull('agent_id')->where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where([
                ['status_id', Status::DISAPPROVED],
                ['approval_status', ApprovalStatusEnum::DISAPPROVED],
                ['user_id', User::role(Role::USER)->where('id', auth()->user()->id)->value('id')]
            ]))->orderByDesc('created_at')->get();
    }

    public function getClosedTickets()
    {
        return Ticket::with(['replies', 'priorityLevel'])
            ->where(fn($statusQuery) => $statusQuery->where([
                ['status_id', Status::CLOSED],
                ['approval_status', ApprovalStatusEnum::APPROVED],
                ['user_id', User::role(Role::USER)->where('id', auth()->user()->id)->value('id')]
            ]))->orderByDesc('created_at')->get();
    }

    public function getLatestReply(int $id)
    {
        return Reply::where([
            ['ticket_id', $id],
            ['user_id', User::role(Role::USER)->where('id', auth()->user()->id)->value('id')]
        ])->orderByDesc('created_at')->first();
    }

    public function getLatestClarification(int $id)
    {
        return Clarification::where([
            ['ticket_id', $id],
            ['user_id', User::role(Role::USER)->where('id', auth()->user()->id)->value('id')]
        ])->orderByDesc('created_at')->first();
    }
}
