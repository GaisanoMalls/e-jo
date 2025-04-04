<?php

namespace App\Http\Livewire\Approver\Ticket;

use Exception;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
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
    public array $approvalLevels = [1, 2, 3, 4, 5];

    protected $listeners = ['loadRecommendationApproval' => '$refresh'];

    /**
     * Fetches the approvers for a specific level of a recommendation.
     *
     * This function retrieves a list of users who are approvers for a given level of a recommendation
     * associated with the current ticket. It performs the following steps:
     * 1. Loads the user profiles and filters users who are associated with the given recommendation level.
     * 2. Checks if the recommendation is linked to the current ticket.
     * 3. Ensures that the approvers are associated with the recommendation's approval status.
     *
     * @param int $level The level of the recommendation for which approvers are being fetched.
     * @param Recommendation $recommendation The recommendation object for which approvers are being fetched.
     * @return \Illuminate\Support\Collection A collection of users who are approvers for the specified level and recommendation.
     */
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

    /**
     * Checks if the currently authenticated user has approved a specific recommendation.
     *
     * This function queries the RecommendationApprover model to determine if the logged-in user
     * has approved the given recommendation for the current ticket. It checks for the existence
     * of an approval record matching:
     * - The current user's ID as the approver
     * - An approval status of true
     * - The specified recommendation
     * - The current ticket context
     *
     * @param \App\Models\Recommendation $recommendation The recommendation to check approval status for
     * @return bool Returns true if the user has approved this recommendation for the current ticket, false otherwise
     */
    public function isApprovalApproved(Recommendation $recommendation)
    {
        return RecommendationApprover::where([
            ['approver_id', auth()->user()->id],
            ['is_approved', true]
        ])
            ->withWhereHas('recommendation', function ($query) use ($recommendation) {
                $query->where([
                    ['ticket_id', $this->ticket->id],
                    ['recommendation_id', $recommendation->id]
                ]);
            })->exists();
    }

    /**
     * Checks if a specific approval level has approved the given recommendation.
     *
     * Verifies whether any approver at the specified level has approved the recommendation
     * for the current ticket. The function checks for the existence of an approval record that matches:
     * - Approval status set to true
     * - The specified approval level
     * - The provided recommendation
     * - The current ticket context
     *
     * @param int $level The approval level to check (e.g., 1 for first-level approval)
     * @param \App\Models\Recommendation $recommendation The recommendation to check approval status for
     * @return bool Returns true if the recommendation is approved at the specified level for the current ticket, false otherwise
     */
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

    /**
     * Checks if the authenticated user is listed as an approver for the given recommendation and ticket.
     *
     * Determines whether the currently logged-in user exists as an approver in the RecommendationApprover
     * records associated with the specified recommendation and ticket. This verification is useful for
     * authorization checks before allowing approval actions.
     *
     * @param \App\Models\Ticket $ticket The ticket containing the recommendation
     * @param \App\Models\Recommendation $recommendation The recommendation to check approver status for
     * @return bool Returns true if the current user is an approver for this recommendation-ticket combination, false otherwise
     */
    public function isApproverInRecommendationApprovers(Ticket $ticket, Recommendation $recommendation)
    {
        return RecommendationApprover::where('approver_id', auth()->user()->id)
            ->withWhereHas('recommendation', function ($query) use ($ticket, $recommendation) {
                $query->where([
                    ['ticket_id', $ticket->id],
                    ['recommendation_id', $recommendation->id]
                ]);
            })->exists();
    }

    /**
     * Approves a pending ticket recommendation and triggers related events.
     * 
     * This method:
     * 1. Finds the first pending recommendation for the current ticket
     * 2. If found:
     *    - Approves the recommendation approval
     *    - Emits events to refresh related UI components
     *    - Logs the approval activity
     * 3. If not found, shows an error notification
     * 4. Handles any exceptions by logging them to both AppErrorLog and Laravel logs
     *
     * @return void
     * @throws Exception If an error occurs during the approval process (handled internally)
     * 
     * @fires loadCustomForm Refreshes custom form data
     * @fires loadRecommendationApproval Refreshes approval status
     * @fires loadTicketLogs Refreshes ticket logs
     * 
     * @uses \App\Models\Recommendation To find the pending recommendation
     * @uses \App\Enums\RecommendationApprovalStatusEnum For PENDING status check
     * @uses ActivityLog::make() To record the approval action
     * @uses noty() For error notification
     */
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

                ActivityLog::make($this->ticket->id, 'approved the ticket');
            } else {
                noty()->addError('Ticket recommendation is not found.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error encountered during approval.', [$e->getMessage()]);
        }
    }

    /**
     * Approves a pending ticket recommendation and triggers related events.
     * 
     * This method:
     * 1. Finds the first pending recommendation for the current ticket
     * 2. If found:
     *    - Approves the recommendation approval
     *    - Emits events to refresh related UI components
     *    - Logs the approval activity
     * 3. If not found, shows an error notification
     * 4. Handles any exceptions by logging them to both AppErrorLog and Laravel logs
     *
     * @return void
     * @throws Exception If an error occurs during the approval process (handled internally)
     * 
     * @fires loadCustomForm Refreshes custom form data
     * @fires loadRecommendationApproval Refreshes approval status
     * @fires loadTicketLogs Refreshes ticket logs
     * 
     * @uses \App\Models\Recommendation To find the pending recommendation
     * @uses \App\Enums\RecommendationApprovalStatusEnum For PENDING status check
     * @uses ActivityLog::make() To record the approval action
     * @uses noty() For error notification
     */
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
                    'date' => Carbon::now()
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

    /**
     * Checks if a recommendation has been requested for the current ticket.
     *
     * Determines whether there exists any recommendation record for the current ticket
     * that has been specifically requested (indicated by a non-null 'requested_by_sda_id' field).
     * This is typically used to verify if the SDA (Service Delivery Advisor) has initiated
     * a recommendation request for this ticket.
     *
     * @return bool Returns true if a recommendation request exists for the current ticket, false otherwise
     */
    public function isRecommendationRequested()
    {
        return Recommendation::where('ticket_id', $this->ticket->id)
            ->whereNotNull('requested_by_sda_id')
            ->exists();
    }

    /**
     * Retrieves all SDA-requested recommendations for the current ticket with related data.
     * 
     * Fetches recommendation records that:
     * - Belong to the current ticket
     * - Were explicitly requested by a Service Department Administrator (non-null requested_by_sda_id)
     * - Includes eager-loaded relationships:
     *   - The requesting SDA's profile data
     *   - Approval status information
     *
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of Recommendation models with loaded relationships, or an empty collection if none found
     * @see \App\Models\Recommendation
     */
    private function getRecommendations()
    {
        return Recommendation::with([
            'requestedByServiceDeptAdmin.profile',
            'approvalStatus'
        ])->where('ticket_id', $this->ticket->id)
            ->whereNotNull('requested_by_sda_id')
            ->get();
    }

    /**
     * Checks if a recommendation was disapproved at a specific approval level.
     *
     * Determines whether the given recommendation has been explicitly disapproved (is_approved = false)
     * at the specified approval level, with a matching DISAPPROVED status in the approval status record.
     *
     * @param int $level The approval level to check (e.g., 1, 2, etc.)
     * @param \App\Models\Recommendation $recommendation The recommendation to verify
     * @return bool Returns true if:
     *              - The recommendation has DISAPPROVED status
     *              - Has a matching approver record at the specified level
     *              - With is_approved set to false
     *             Returns false otherwise
     *
     * @uses \App\Enums\RecommendationApprovalStatusEnum For DISAPPROVED status check
     */
    public function isDisApprovedRecommendationLevel(int $level, Recommendation $recommendation)
    {
        return RecommendationApprover::withWhereHas('recommendation.approvalStatus', function ($status) use ($recommendation) {
            $status->where('approval_status', RecommendationApprovalStatusEnum::DISAPPROVED);
        })
            ->where([
                ['level', $level],
                ['is_approved', false],
                ['recommendation_id', $recommendation->id]
            ])->exists();
    }

    /**
     * Determines if a recommendation has been officially disapproved with a reason.
     *
     * Checks whether the specified recommendation has both:
     * - A DISAPPROVED status in its approval status record
     * - A documented disapproval reason (non-null disapproved_reason)
     *
     * This provides a complete check for formal disapproval cases where both the status
     * and justification are required.
     *
     * @param \App\Models\Recommendation $recommendation The recommendation to check
     * @return bool Returns true if the recommendation is:
     *              - Marked as DISAPPROVED
     *              - Has a disapproval reason specified
     *              Returns false otherwise
     *
     * @uses \App\Enums\RecommendationApprovalStatusEnum For DISAPPROVED status check
     */
    public function isDisapprovedRecommendation(Recommendation $recommendation)
    {
        return Recommendation::withWhereHas('approvalStatus', function ($status) use ($recommendation) {
            $status->where([
                ['recommendation_id', $recommendation->id],
                ['approval_status', RecommendationApprovalStatusEnum::DISAPPROVED]
            ])->whereNotNull('disapproved_reason');
        })->exists();
    }

    public function render()
    {
        $this->recommendations = $this->getRecommendations();
        $this->approvalHistory = Recommendation::with(['approvalStatus', 'approvers', 'requestedByServiceDeptAdmin'])
            ->where('ticket_id', $this->ticket->id)
            ->orderByDesc('created_at')
            ->get();
        $this->currentRecommendation = Recommendation::with('approvalStatus')
            ->where('ticket_id', $this->ticket->id)
            ->latest('created_at')
            ->first();
        $this->latestRecommendation = Recommendation::where('ticket_id', $this->ticket->id)
            ->withWhereHas('approvalStatus', fn($status) => $status->where('approval_status', RecommendationApprovalStatusEnum::PENDING))
            ->latest('created_at')
            ->first();
        return view('livewire.approver.ticket.recommendation-approval');
    }
}
