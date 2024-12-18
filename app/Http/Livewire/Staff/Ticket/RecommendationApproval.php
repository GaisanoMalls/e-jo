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
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class RecommendationApproval extends Component
{
    public ?Ticket $ticket;
    public ?Collection $recommendations = null;
    public ?Collection $approvalHistory;
    public ?Recommendation $newRecommendation = null;
    public ?Recommendation $currentRecommendation = null;
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
            $recommendation = Recommendation::where([
                ['ticket_id', $this->ticket->id],
                ['approval_status', RecommendationApprovalStatusEnum::PENDING]
            ])->first();

            if ($recommendation->exists()) {
                $recommendation->update([
                    'approval_status' => RecommendationApprovalStatusEnum::APPROVED
                ]);

                $events = ['loadCustomForm', 'loadRecommendationApproval', 'loadTicketLogs'];
                foreach ($events as $event) {
                    $this->emit($event);
                }

                Notification::send(
                    $recommendation->requestedByServiceDeptAdmin,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Approved request)",
                        message: "Approval request has been approved."
                    )
                );
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
            $recommendation = Recommendation::where([
                ['ticket_id', $this->ticket->id],
                ['approval_status', RecommendationApprovalStatusEnum::PENDING]
            ])->first();

            if ($recommendation->exists()) {
                $recommendation->update([
                    'approval_status' => RecommendationApprovalStatusEnum::DISAPPROVED
                ]);

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

    private function getRecommendations()
    {
        return Recommendation::with('requestedByServiceDeptAdmin.profile')
            ->where([
                ['ticket_id', $this->ticket->id],
                ['is_requesting_ict_approval', true],
                ['requested_by_sda_id', '!=', null]
            ])->get();
    }

    public function render()
    {
        $this->recommendations = $this->getRecommendations();
        $this->approvalHistory = Recommendation::where('ticket_id', $this->ticket->id)->orderByDesc('created_at')->get();
        $this->currentRecommendation = Recommendation::where('ticket_id', $this->ticket->id)->latest('created_at')->first();
        $this->newRecommendation = Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['approval_status', RecommendationApprovalStatusEnum::PENDING]
        ])->latest('created_at')
            ->first();

        return view('livewire.staff.ticket.recommendation-approval');
    }
}
