<?php

namespace App\Http\Livewire\Approver\Ticket;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use Livewire\Component;
use App\Models\ActivityLog;
use App\Models\Recommendation;
use App\Http\Traits\AppErrorLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\RecommendationApprover;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Notification;
use App\Enums\RecommendationApprovalStatusEnum;
use App\Http\Traits\RecommendationApproval as RecommendationApprovalTrait;

class RecommendationApproval extends Component
{
    use RecommendationApprovalTrait, AppErrorLog;

    public Ticket $ticket;
    public ?string $disapprovedReason = null;
    public ?Collection $recommendations = null;
    public ?Collection $approvalHistory = null;
    public ?Recommendation $newRecommendation = null;
    public ?Recommendation $currentRecommendation = null;
    public bool $isAllowedToApproveRecommendation = false;
    public array $approvalLevels = [1, 2, 3, 4, 5];

    protected $listeners = ['loadRecommendationApproval' => '$refresh'];

    public function fetchApprovers(int $level)
    {
        return User::with('profile')
            ->withWhereHas('recommendationApprovers', function ($query) use ($level) {
                $query->where('level', $level)
                    ->withWhereHas('recommendation', function ($recommendation) {
                        $recommendation->with('approvers')
                            ->where('ticket_id', $this->ticket->id);
                    });
            })->get();
    }

    public function isApprovalApproved()
    {
        return RecommendationApprover::where([
            ['approver_id', auth()->user()->id],
            ['is_approved', true]
        ])
            ->withWhereHas('recommendation', function ($recommendation) {
                $recommendation->where('ticket_id', $this->ticket->id);
            })->exists();
    }

    public function isLevelApproved(int $level)
    {
        return RecommendationApprover::where([
            ['is_approved', true],
            ['level', $level]
        ])->withWhereHas('recommendation', function ($recommendation) {
            $recommendation->where('ticket_id', $this->ticket->id);
        })->exists();
    }

    public function isApproverInRecommendationApprovers(Ticket $ticket)
    {
        return RecommendationApprover::where('approver_id', auth()->user()->id)
            ->withWhereHas('recommendation', function ($recommendation) use ($ticket) {
                $recommendation->where('ticket_id', $ticket->id);
            })->exists();
    }

    public function approveTicketRecommendation()
    {
        try {
            $recommendation = Recommendation::where('ticket_id', $this->ticket->id)
                ->withWhereHas('approvalStatus', fn($status) => $status->where('approval_status', RecommendationApprovalStatusEnum::PENDING))
                ->first();

            if ($recommendation->exists()) {
                $this->approveRecommendationApproval($this->ticket);

                $events = ['loadCustomForm', 'loadRecommendationApproval', 'loadTicketLogs'];
                foreach ($events as $event) {
                    $this->emit($event);
                }

                // Notification::send(
                //     $recommendation->requestedByServiceDeptAdmin,
                //     new AppNotification(
                //         ticket: $this->ticket,
                //         title: "Ticket #{$this->ticket->ticket_number} (Approved request)",
                //         message: "Approval request has been approved."
                //     )
                // );
                ActivityLog::make($this->ticket->id, 'approved the ticket');
            } else {
                noty()->addError('Ticket recommendation is not found.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error encountered during approval.', [$e->getMessage()]);
        }
    }

    public function disapproveTicketRecommendation()
    {
        try {
            $recommendation = Recommendation::where('ticket_id', $this->ticket->id)
                ->withWhereHas('approvalStatus', fn($status) => $status->where('approval_status', RecommendationApprovalStatusEnum::PENDING))
                ->first();

            if ($recommendation->exists()) {
                if ($this->disapprovedReason == null) {
                    $this->addError('disapprovedReason', 'Please enter a reason.');
                    return;
                }

                $recommendation->approvalStatus()->update([
                    'approval_status' => RecommendationApprovalStatusEnum::DISAPPROVED,
                    'disapproved_reason' => $this->disapprovedReason,
                ]);

                // 'approval_status' => RecommendationApprovalStatusEnum::DISAPPROVED
                $events = ['loadCustomForm', 'loadRecommendationApproval', 'loadTicketLogs'];
                foreach ($events as $event) {
                    $this->emit($event);
                }

                Notification::send(
                    $recommendation->requestedByServiceDeptAdmin,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Disapproved request)",
                        message: "Approval request has been disapproved."
                    )
                );

                $this->dispatchBrowserEvent('close-ticket-recommendation-disapproval-modal');
                $this->reset('disapprovedReason');

                ActivityLog::make($this->ticket->id, 'disapproved the ticket');
            } else {
                noty()->addError('Ticket recommendation is not found.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error encountered during disapproval.', [$e->getMessage()]);
        }
    }

    public function isRecommendationRequested()
    {
        return Recommendation::where('ticket_id', $this->ticket->id)
            ->whereNotNull('requested_by_sda_id')
            ->exists();
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
        $this->recommendations = $this->getRecommendations();
        $this->approvalHistory = Recommendation::with('approvalStatus')
            ->where('ticket_id', $this->ticket->id)
            ->orderByDesc('created_at')
            ->get();
        $this->currentRecommendation = Recommendation::with('approvalStatus')
            ->where('ticket_id', $this->ticket->id)
            ->latest('created_at')
            ->first();
        $this->newRecommendation = Recommendation::where('ticket_id', $this->ticket->id)
            ->withWhereHas('approvalStatus', fn($status) => $status->where('approval_status', RecommendationApprovalStatusEnum::PENDING))
            ->latest('created_at')
            ->first();

        return view('livewire.approver.ticket.recommendation-approval');
    }
}
