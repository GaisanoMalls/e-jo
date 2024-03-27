<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\Utils;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    use Utils;

    public function serviceDeptAdminGetTicketsToAssign()
    {
        return Ticket::whereHas('teams')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::APPROVED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->withWhereHas('ticketCosting', fn($ticketCosting) => $ticketCosting->withWhereHas('prFileAttachments', fn($prFile) =>
                $prFile->where([
                    ['is_approved_level_1_approver', true],
                    ['is_approved_level_2_approver', true],
                ])))
            ->withWhereHas('ticketApprovals', fn($ticketApproval) => $ticketApproval->where([
                ['approval_1->level_1_approver->is_approved', true],
                ['approval_1->level_2_approver->is_approved', true],
                ['approval_1->is_all_approved', true],
            ]))
            ->withWhereHas('specialProjectAmountApproval', fn($spAmountApproval) => $spAmountApproval->where([
                ['service_department_admin_approver->is_approved', true],
                ['fpm_coo_approver->is_approved', true],
                ['is_done', true]
            ]))
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Filter the newly created tickets.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     * Special Project - Costing: Include filter for ticket that has amount for special project and is approved.
     */
    public function serviceDeptAdminGetOpentTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orWhere(fn($query) => $query->withWhereHas('specialProjectAmountApproval', fn($spAmountApproval) => $spAmountApproval->where('is_done', true)))
            ->withWhereHas('ticketApprovals', fn($ticketApproval) => $ticketApproval->where([
                ['approval_1->level_1_approver->is_approved', false],
                ['approval_1->level_2_approver->is_approved', false],
                ['approval_1->is_all_approved', false],
            ]))
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Filter the newly created tickets.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     */
    public function serviceDeptAdminGetViewedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->whereDoesntHave('specialProjectAmountApproval')
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetApprovedTickets()
    {
        if ($this->isApproved2LevelsOfApproverAndHasSpecialProject()) {
            return Ticket::where([
                ['status_id', Status::APPROVED],
                ['approval_status', ApprovalStatusEnum::APPROVED],
            ])->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
                ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                ->withWhereHas('ticketApprovals', fn($ticketApproval) =>
                    $ticketApproval->whereNotNull('approval_1->level_1_approver->approver_id')
                        ->whereNotNull('approval_1->level_2_approver->approver_id')
                        ->whereNotNull('approval_1->level_1_approver->approved_by')
                        ->whereNotNull('approval_1->level_2_approver->approved_by')
                        ->where([
                            ['approval_1->level_1_approver->is_approved', true],
                            ['approval_1->level_2_approver->is_approved', true],
                            ['approval_1->is_all_approved', true],
                        ]))
                ->orderByDesc('created_at')->get();
        }

        return Ticket::where([
            ['status_id', Status::APPROVED],
            ['approval_status', ApprovalStatusEnum::APPROVED],
        ])->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::DISAPPROVED)->where('approval_status', ApprovalStatusEnum::DISAPPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClaimedTickets()
    {
        return Ticket::whereHas('agent')
            ->where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray()))
            ->whereHas('ticketApprovals', fn($ticketApproval) =>
                $ticketApproval->orWhere([
                    ['approval_2->level_1_approver->approver_id', auth()->user()->id],
                    ['approval_2->level_1_approver->approved_by', null],
                    ['approval_2->level_1_approver->is_approved', false],
                ]))
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetOnProcessTickets()
    {
        $ticketIsNotYetApproved = Ticket::with(['clarifications', 'helpTopic.specialProject'])
            ->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL)
            ->where('status_id', Status::ON_PROCESS)
            ->exists();

        if ($ticketIsNotYetApproved) {
            return Ticket::with(['clarifications', 'helpTopic.specialProject'])
                ->where(function ($query) {
                    $query->whereHas('clarifications')
                        ->orWhereHas('helpTopic.specialProject');
                })->where(fn($query) => $query->where('status_id', Status::ON_PROCESS)
                    ->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL))
                ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                    ->withWhereHas('user.buDepartments', fn($query) => $query->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray())))
                ->orderByDesc('created_at')
                ->get();
        } else {
            if ($this->isApproved2LevelsOfApproverAndHasSpecialProject()) {
                return Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
                    ->where(function ($query) {
                        $query->whereHas('replies')
                            ->orWhereHas('clarifications')
                            ->orWhereHas('helpTopic.specialProject');
                    })->where(fn($query) => $query->where('status_id', Status::ON_PROCESS)
                        ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
                    ->where(fn($withSpecialProjQuery) => $withSpecialProjQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray()))
                    ->orderByDesc('created_at')
                    ->get();
            } else {
                return Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
                    ->where(function ($query) {
                        $query->whereHas('replies')
                            ->orWhereHas('clarifications')
                            ->orWhereHas('helpTopic.specialProject');
                    })->where(fn($query) => $query->where('status_id', Status::ON_PROCESS)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
                    ->where(fn($withSpecialProjQuery) => $withSpecialProjQuery->withWhereHas('user.branches', fn($query) =>
                        $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                        ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
                    ->where(fn($nonSpecialProjQuery) => $nonSpecialProjQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                        ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray()))
                    ->orderByDesc('created_at')
                    ->get();
            }
        }
    }

    public function serviceDeptAdminGetOverdueTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OVERDUE)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')
            ->get();
    }

    public function serviceDeptAdminGetClosedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('branch', fn($query) => $query->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('serviceDepartment', fn($query) => $query->where('service_department_id', auth()->user()->serviceDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')
            ->get();
    }
}
