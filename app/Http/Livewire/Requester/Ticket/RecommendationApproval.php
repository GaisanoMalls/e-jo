<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Recommendation;
use App\Models\RecommendationApprover;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use App\Http\Traits\RecommendationApproval as RecommendationApprovalTrait;


class RecommendationApproval extends Component
{
    use RecommendationApprovalTrait, AppErrorLog;

    public Ticket $ticket;
    public ?Collection $recommendations = null;
    public ?Collection $approvalHistory = null;
    public array $approvalLevels = [1, 2, 3, 4, 5];

    protected $listeners = ['loadRecommendationApproval' => '$refresh'];

    public function mount()
    {
        $this->recommendations = $this->getRecommendations();
        $this->approvalHistory = Recommendation::with(['approvalStatus', 'approvers', 'requestedByServiceDeptAdmin'])
            ->where('ticket_id', $this->ticket->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function fetchApprovers(int $level, Recommendation $recommendation)
    {
        return User::with('profile')
            ->withWhereHas('recommendationApprovers', function ($query) use ($level, $recommendation) {
                $query->where('level', $level)
                    ->withWhereHas('recommendation', function ($query) use ($recommendation) {
                        $query->with('approvers')
                            ->where('ticket_id', $this->ticket->id)
                            ->withWhereHas('approvalStatus', fn($status) => $status->where('recommendation_id', $recommendation->id));
                    });
            })->get();
    }

    public function isRecommendationRequested()
    {
        return Recommendation::where('ticket_id', $this->ticket->id)
            ->whereNotNull('requested_by_sda_id')
            ->exists();
    }

    public function isLevelApproved(int $level, Recommendation $recommendation)
    {
        return RecommendationApprover::where([
            ['is_approved', true],
            ['level', $level]
        ])->withWhereHas('recommendation', function ($query) use ($recommendation) {
            $query->where([
                ['ticket_id', $this->ticket->id],
                ['recommendation_id', $recommendation->id],
            ]);
        })->exists();
    }

    public function isDisApprovedRecommendationLevel(int $level, Recommendation $recommendation)
    {
        return RecommendationApprover::withWhereHas('recommendation.approvalStatus', function ($status) {
            $status->where('approval_status', RecommendationApprovalStatusEnum::DISAPPROVED);
        })
            ->where([
                ['recommendation_id', $recommendation->id],
                ['level', $level],
                ['is_approved', false]
            ])->exists();
    }

    public function isDisapprovedRecommendation(Recommendation $recommendation)
    {
        return Recommendation::withWhereHas('approvalStatus', function ($status) use ($recommendation) {
            $status->where([
                ['recommendation_id', $recommendation->id],
                ['approval_status', RecommendationApprovalStatusEnum::DISAPPROVED]
            ])->whereNotNull('disapproved_reason');
        })->exists();
    }

    private function getRecommendations()
    {
        return Recommendation::with([
            'requestedByServiceDeptAdmin.profile',
            'approvalStatus'
        ])->where('ticket_id', $this->ticket->id)
            ->whereNotNull('requested_by_sda_id')
            ->get();
    }

    public function render()
    {
        return view('livewire.requester.ticket.recommendation-approval');
    }
}
