<?php

namespace App\Http\Traits;

use App\Models\RecommendationApprovalLevel;
use App\Models\RecommendationApprover;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Notification;

trait RecommendationApproval
{
    private array $levelsOfApproval = [1, 2, 3, 4, 5];

    protected function isApproverIsInRecommendationApprovers(Ticket $ticket)
    {
        return RecommendationApprover::where('approver_id', auth()->user()->id)
            ->withWhereHas('approvalLevel.recommendation', function ($recommendation) use ($ticket) {
                $recommendation->where('ticket_id', $ticket->id);
            })->exists();
    }

    private static function sendNotificationToNextApprover(Ticket $ticket, User $approver)
    {
        Notification::send($approver, new AppNotification(
            ticket: $ticket,
            title: "Ticket #{$ticket->ticket_number} (For approval)",
            message: "You have a new ticket for approval",
        ));
    }

    private function approveRecommendationApproval(Ticket $ticket)
    {
        $isAllLevelApproved = true;
        $currentLevel = 1; // keep track of the current level

        foreach ($this->levelsOfApproval as $level) {
            $recommendationApprovals = RecommendationApprovalLevel::with('approvers')
                ->where('level', $level)
                ->withWhereHas('recommendation', function ($recommendation) use ($ticket) {
                    $recommendation->where('ticket_id', $ticket->id);
                })->get();
        }
    }
}