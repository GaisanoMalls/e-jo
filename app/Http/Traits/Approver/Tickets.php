<?php

namespace App\Http\Traits\Approver;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\BasicModelQueries;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

trait Tickets
{
    use BasicModelQueries;

    public function getOpenTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::OPEN)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::DISAPPROVED)
                    ->where('approval_status', ApprovalStatusEnum::DISAPPROVED);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getViewedTickets()
    {
        return Ticket::where('status_id', Status::VIEWED)
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $approval->where('is_approved', false)
                    ->whereHas('helpTopicApprover', function ($approver) {
                        $approver->where('user_id', auth()->user()->id);
                    });
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    // ------------------------------------------------------------------------------------
    // For COO Approver Only
    public function getForApprovalCostings()
    {
        $tickets = Ticket::has('ticketCosting')
            ->has('specialProjectAmountApproval')
            ->with('helpTopic.specialProject')->get();

        $costingsForApproval = [];
        $cooApproverId = User::role(Role::APPROVER)
            ->where('id', auth()->user()->id)
            ->value('id');

        foreach ($tickets as $ticket) {
            $costingsForApproval = Ticket::whereHas('specialProjectAmountApproval', function ($spAmountApproval) use ($cooApproverId) {
                $spAmountApproval->whereNotNull([
                    'service_department_admin_approver->approver_id',
                    'service_department_admin_approver->date_approved'
                ])
                    ->whereJsonContains('service_department_admin_approver->is_approved', true)
                    ->whereJsonContains('fpm_coo_approver->approver_id', $cooApproverId);
            })
                ->whereHas('ticketCosting', function ($costing) use ($ticket) {
                    $costing->where('amount', '>=', (float) $ticket->helpTopic->specialProject->amount);
                })
                ->orderByDesc('created_at')
                ->get();
        }

        return $costingsForApproval;
    }

    public function getApprovedCostings()
    {
        $tickets = Ticket::has('ticketCosting')
            ->has('specialProjectAmountApproval')
            ->with('helpTopic.specialProject')
            ->get();
    }
}
