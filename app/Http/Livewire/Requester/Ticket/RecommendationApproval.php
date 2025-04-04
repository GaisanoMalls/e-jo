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
