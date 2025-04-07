<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\Recommendation;
use App\Models\RecommendationApprover;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Collection;
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
        // Get recommendations for the current ticket and store in property
        $this->recommendations = $this->getRecommendations();

        // Load approval history with related data, ordered by creation date (newest first)
        $this->approvalHistory = Recommendation::with([
            'approvalStatus',                   // Approval status relationship
            'approvers',                        // Approvers relationship
            'requestedByServiceDeptAdmin'       // Requesting admin relationship
        ])
            ->where('ticket_id', $this->ticket->id) // Filter by current ticket
            ->orderByDesc('created_at') // Newest records first
            ->get();
    }

    /**
     * Fetches approvers for a specific recommendation approval level.
     *
     * Retrieves users who are authorized approvers for:
     * - The specified approval level
     * - The given recommendation
     * - The current ticket context
     *
     * Includes user profiles and nested recommendation approval relationships.
     *
     * @param int $level The approval level to filter by (e.g., 1, 2, etc.)
     * @param Recommendation $recommendation The recommendation to check approvers for
     * @return \Illuminate\Database\Eloquent\Collection Returns collection of User models with:
     *         - Profile data loaded
     *         - Recommendation approver relationships
     *         - Filtered by level and ticket context
     */
    public function fetchApprovers(int $level, Recommendation $recommendation)
    {
        return User::with('profile') // Eager load user profiles
            ->withWhereHas('recommendationApprovers', function ($query) use ($level, $recommendation) {
                $query->where('level', $level) // Filter by approval level
                    ->withWhereHas('recommendation', function ($query) use ($recommendation) {
                        // Load nested relationships and apply filters:
                        $query->with('approvers')  // Load approvers relationship
                            ->where('ticket_id', $this->ticket->id) // Current ticket only
                            ->withWhereHas('approvalStatus', fn($status) => $status->where('recommendation_id', $recommendation->id));
                    });
            })->get();
    }

    /**
     * Checks if a recommendation has been formally requested for the current ticket.
     *
     * Determines whether there exists any recommendation record for the current ticket
     * that was explicitly requested by a Service Department Administrator (SDA),
     * indicated by a non-null requested_by_sda_id field.
     *
     * @return bool Returns true if a requested recommendation exists for the current ticket,
     *              false otherwise
     *
     * @uses \App\Models\Recommendation For recommendation records
     */
    public function isRecommendationRequested()
    {
        // Query the Recommendation model for records that:
        return Recommendation::where('ticket_id', $this->ticket->id) // Belong to current ticket
            ->whereNotNull('requested_by_sda_id') // Have a requesting SDA (non-null)
            ->exists(); // Return boolean if any matching records exist
    }

    /**
     * Checks if a recommendation has been approved at a specific level.
     *
     * Verifies whether the given recommendation has been approved:
     * 1. At the specified approval level
     * 2. For the current ticket
     * 3. By checking for existing approved records in RecommendationApprover
     *
     * @param int $level The approval level to check (e.g., 1 for first-level approval)
     * @param Recommendation $recommendation The recommendation to verify
     * @return bool Returns true if an approved record exists matching all criteria,
     *              false otherwise
     *
     * @uses \App\Models\RecommendationApprover For approval records
     */
    public function isLevelApproved(int $level, Recommendation $recommendation)
    {
        return RecommendationApprover::where([
            ['is_approved', true],  // Only approved records
            ['level', $level]       // Matching the specified level
        ])
            ->withWhereHas('recommendation', function ($query) use ($recommendation) {
                $query->where([
                    ['ticket_id', $this->ticket->id],           // For current ticket
                    ['recommendation_id', $recommendation->id]  // And specific recommendation
                ]);
            })
            ->exists(); // Return boolean result
    }

    /**
     * Checks if a recommendation was disapproved at a specific approval level.
     *
     * Verifies three conditions for disapproval:
     * 1. The recommendation has DISAPPROVED status in approvalStatus
     * 2. The specified approval level matches
     * 3. The approver explicitly marked as not approved (is_approved = false)
     *
     * @param int $level The approval level to check (e.g., 1, 2)
     * @param Recommendation $recommendation The recommendation to verify
     * @return bool Returns true if:
     *              - Recommendation is disapproved at specified level
     *              - With explicit rejection flag
     *             Returns false otherwise
     *
     * @uses \App\Models\RecommendationApprover For approval records
     * @uses \App\Enums\RecommendationApprovalStatusEnum For DISAPPROVED status
     */
    public function isDisApprovedRecommendationLevel(int $level, Recommendation $recommendation)
    {
        return RecommendationApprover::withWhereHas('recommendation.approvalStatus', function ($status) {
            // Check for DISAPPROVED status in related approvalStatus
            $status->where('approval_status', RecommendationApprovalStatusEnum::DISAPPROVED);
        })
            ->where([
                ['recommendation_id', $recommendation->id],  // Specific recommendation
                ['level', $level],                          // Specified approval level
                ['is_approved', false]                      // Explicit disapproval
            ])
            ->exists();  // Boolean result
    }

    /**
     * Verifies if a recommendation is officially disapproved with a documented reason.
     *
     * Checks for a formal disapproval by verifying:
     * 1. The recommendation has DISAPPROVED status
     * 2. A disapproval reason is specified (not null)
     * 3. The status record matches the specific recommendation
     *
     * This ensures only properly documented disapprovals are recognized.
     *
     * @param Recommendation $recommendation The recommendation to check
     * @return bool Returns true if:
     *              - Recommendation is marked DISAPPROVED
     *              - Has a non-null disapproval reason
     *              - Status matches the recommendation
     *             Returns false otherwise
     *
     * @uses \App\Models\Recommendation For recommendation records
     * @uses \App\Enums\RecommendationApprovalStatusEnum For status values
     */
    public function isDisapprovedRecommendation(Recommendation $recommendation)
    {
        return Recommendation::withWhereHas('approvalStatus', function ($status) use ($recommendation) {
            $status->where([
                ['recommendation_id', $recommendation->id],  // Match specific recommendation
                ['approval_status', RecommendationApprovalStatusEnum::DISAPPROVED]  // Disapproved status
            ])->whereNotNull('disapproved_reason');  // Must have reason specified
        })->exists();  // Boolean result
    }

    /**
     * Retrieves all SDA-requested recommendations for the current ticket with related data.
     *
     * Fetches recommendation records that:
     * - Belong to the current ticket (ticket_id match)
     * - Were formally requested by a Service Department Administrator (non-null requested_by_sda_id)
     * - Includes eager-loaded relationships:
     *   - Requesting SDA's profile data
     *   - Approval status information
     *
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of:
     *         - Recommendation models
     *         - With loaded relationships
     *         - Ordered by creation date (descending)
     *         Empty collection if no matching records found
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If ticket not found
     * @uses \App\Models\Recommendation For recommendation records
     */
    private function getRecommendations()
    {
        return Recommendation::with([
            'requestedByServiceDeptAdmin.profile',  // Load SDA requester's profile
            'approvalStatus'                       // Load approval status
        ])
            ->where('ticket_id', $this->ticket->id)    // Filter by current ticket
            ->whereNotNull('requested_by_sda_id')      // Only formally requested recommendations
            ->orderByDesc('created_at')                // Newest first
            ->get(); // Return collection of recommendations
    }

    public function render()
    {
        return view('livewire.requester.ticket.recommendation-approval');
    }
}
