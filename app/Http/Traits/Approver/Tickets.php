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

    public function getForApprovalTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where('approval_status', ApprovalStatusEnum::APPROVED)
            ->whereNotIn('status_id', [
                Status::VIEWED,
                Status::DISAPPROVED,
                Status::APPROVED,
                Status::ON_PROCESS
            ])
            ->withWhereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', User::where('id', auth()->user()->id)->role(Role::APPROVER)->first('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOpenTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::OPEN)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->withWhereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', callback: function ($approver) {
                $approver->where('user_id', User::where('id', auth()->user()->id)->role(Role::APPROVER)->first('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getDisapprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::CLOSED)
                    ->where('approval_status', ApprovalStatusEnum::DISAPPROVED);
            })
            ->withWhereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', User::where('id', auth()->user()->id)->role(Role::APPROVER)->first('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getViewedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::VIEWED)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->withWhereHas('user.buDepartments', callback: function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', User::where('id', auth()->user()->id)->role(Role::APPROVER)->first('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getApprovedTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::APPROVED)
                    ->where('approval_status', ApprovalStatusEnum::APPROVED);
            })
            ->withWhereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', User::where('id', auth()->user()->id)->role(Role::APPROVER)->first('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOnProcessTickets()
    {
        return Ticket::has('helpTopic.specialProject')
            ->where(function ($statusQuery) {
                $statusQuery->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [
                        ApprovalStatusEnum::APPROVED,
                        ApprovalStatusEnum::FOR_APPROVAL
                    ]);
            })
            ->withWhereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('ticketApprovals.helpTopicApprover', callback: function ($approver) {
                $approver->where('user_id', User::where('id', auth()->user()->id)->role(Role::APPROVER)->first('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    // ------------------------------------------------------------------------------------
    // For COO Approver Only
    public function getForApprovalCostings()
    {
        $tickets = Ticket::has('helpTopic.specialProject')
            ->has('ticketCosting')
            ->has('specialProjectAmountApproval')
            ->with('helpTopic.specialProject')->get();

        $costingsForApproval = [];
        $cooApproverId = User::role(Role::APPROVER)
            ->where('id', auth()->user()->id)
            ->value('id');

        foreach ($tickets as $ticket) {
            $costingsForApproval = Ticket::withWhereHas('specialProjectAmountApproval', function ($spAmountApproval) use ($cooApproverId) {
                $spAmountApproval->whereNotNull([
                    'service_department_admin_approver->approver_id',
                    'service_department_admin_approver->date_approved'
                ])
                    ->whereJsonContains('service_department_admin_approver->is_approved', true)
                    ->whereJsonContains('fpm_coo_approver->approver_id', $cooApproverId);
            })
                ->withWhereHas('ticketCosting', function ($costing) use ($ticket) {
                    $costing->where('amount', '>=', (float) $ticket->helpTopic->specialProject->amount);
                })
                ->orderByDesc('created_at')
                ->get();
        }

        return $costingsForApproval;
    }

    public function getApprovedCostings()
    {
        $tickets = Ticket::has('helpTopic.specialProject')
            ->has('ticketCosting')
            ->has('specialProjectAmountApproval')
            ->with('helpTopic.specialProject')
            ->get();
    }
}
