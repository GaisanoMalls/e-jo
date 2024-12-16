<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Recommendation;
use App\Models\RecommendationApprover;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RecommendationApproval extends Component
{
    public ?Ticket $ticket;
    public ?Recommendation $recommendation = null;
    public Collection $recommendationApprovers;
    public bool $isAllowedToApproveRecommendation = false;

    protected $listeners = ['loadRecommendationApproval' => '$refresh'];

    public function mount()
    {
        $this->isAllowedToApproveRecommendation = $this->isAllowedToApproveRecommendation();
    }

    private function isAllowedToApproveRecommendation()
    {
        return Recommendation::where('ticket_id', $this->ticket->id)
            ->withWhereHas('approvalLevels.approvers', function ($approver) {
                $approver->where('approver_id', auth()->user()->id);
            })->exists();
    }

    public function approveTicketRecommendation()
    {
        try {
            $this->recommendation->where('ticket_id', $this->ticket->id)
                ->update([
                    'approval_status' => RecommendationApprovalStatusEnum::APPROVED
                ]);

            $events = ['loadCustomForm', 'loadTicketLogs'];
            foreach ($events as $event) {
                $this->emit($event);
            }

            ActivityLog::make($this->ticket->id, 'approved the ticket');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error encountered during approval.', [$e->getMessage()]);
        }
    }

    public function disapproveTicketRecommendation()
    {
        try {
            $this->recommendation->where('ticket_id', $this->ticket->id)
                ->update([
                    'approval_status' => RecommendationApprovalStatusEnum::DISAPPROVED
                ]);

            $events = ['loadCustomForm', 'loadTicketLogs'];
            foreach ($events as $event) {
                $this->emit($event);
            }

            ActivityLog::make($this->ticket->id, 'disapproved the ticket');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error encountered during disapproval.', [$e->getMessage()]);
        }
    }

    public function isTicketRecommendationIsPending()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['approval_status', RecommendationApprovalStatusEnum::PENDING]
        ])->exists();
    }

    public function isTicketRecommendationIsApproved()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['approval_status', RecommendationApprovalStatusEnum::APPROVED]
        ])->exists();
    }


    public function isTicketRecommendationIsDisapproved()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['approval_status', RecommendationApprovalStatusEnum::DISAPPROVED]
        ])->exists();
    }

    public function isRecommendationRequested()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_requesting_ict_approval', true],
        ])->exists();
    }

    /**
     * Verify whether the business unit of the ticket requester matches the business unit of the Service Department Admin.
     */
    public function isRequesterServiceDeptAdmin()
    {
        return User::where('id', auth()->user()->id)
            ->withWhereHas('branches', function ($branch) {
                $branch->whereIn('branches.id', $this->ticket->user->branches->pluck('id')->toArray());
            })
            ->withWhereHas('buDepartments', function ($department) {
                $department->whereIn('departments.id', $this->ticket->user->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
            ->exists();
    }

    private function getRecommendationRequester()
    {
        return Recommendation::with('requestedByServiceDeptAdmin.profile')
            ->where([
                ['ticket_id', $this->ticket->id],
                ['is_requesting_ict_approval', true],
                ['requested_by_sda_id', '!=', null]
            ])->first();
    }

    private function getRecommendationApprovers()
    {
        return RecommendationApprover::with('approver.profile')
            ->withWhereHas('approvalLevel.recommendation', function ($recommendation) {
                $recommendation->where('ticket_id', $this->ticket->id);
            })->get();
    }

    public function render()
    {
        $this->recommendation = $this->getRecommendationRequester();
        $this->recommendationApprovers = $this->getRecommendationApprovers();

        return view('livewire.staff.ticket.recommendation-approval');
    }
}
