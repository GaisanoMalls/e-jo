<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Enums\ApprovalStatusEnum;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function serviceDeptAdminGetTicketsToAssign()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatusEnum::APPROVED)->where('status_id', '!=', Status::CLAIMED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->withWhereHas('teams', fn($team) => $team->whereNull('teams.id'))->orderByDesc('created_at')->get();
    }

    /**
     * Filter the newly created tickets send by the requester.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     */
    public function serviceDeptAdminGetOpentTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OPEN)->where('approval_status', ApprovalStatusEnum::FOR_APPROVAL))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    /**
     * Filter the newly created tickets send by the requester.
     * Condition: Requester and Service Dept. Admin - Match the Branch and BU Department.
     * Tickets are exclusively visible within their respective Business Unit (BU).
     */
    public function serviceDeptAdminGetViewedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::VIEWED)->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetApprovedTickets()
    {
        return Ticket::where([
            ['status_id', Status::APPROVED],
            ['approval_status', ApprovalStatusEnum::APPROVED],
        ])->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->withWhereHas('ticketApprovals', fn($ticketApproval) =>
                $ticketApproval->whereNotNull('level_1_approver->approver_id')
                    ->whereNotNull('level_2_approver->approver_id')
                    ->whereNotNull('level_1_approver->approved_by')
                    ->whereNotNull('level_2_approver->approved_by')
                    ->where([
                        ['level_1_approver->is_approved', true],
                        ['level_2_approver->is_approved', true],
                        ['is_all_approved', true],
                    ]))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetDisapprovedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::DISAPPROVED)->where('approval_status', ApprovalStatusEnum::DISAPPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetClaimedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLAIMED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetOnProcessTickets()
    {
        $ticketHasAllApproved = Ticket::withWhereHas('ticketApprovals', fn($ticketApproval) =>
            $ticketApproval->whereNotNull('level_1_approver->approver_id')
                ->whereNotNull('level_2_approver->approver_id')
                ->whereNotNull('level_1_approver->approved_by')
                ->whereNotNull('level_2_approver->approved_by')
                ->where([
                    ['level_1_approver->is_approved', true],
                    ['level_2_approver->is_approved', true],
                    ['is_all_approved', true],
                ]))->get();

        $ticketQuery = Ticket::with(['replies', 'clarifications', 'helpTopic.specialProject'])
            ->where(function ($query) {
                $query->whereHas('replies')
                    ->orWhereHas('clarifications')
                    ->orWhereHas('helpTopic.specialProject');
            })
            ->where(function ($query) {
                $query->where('status_id', Status::ON_PROCESS)
                    ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]);
            })->orderByDesc('created_at')->get();

        if ($ticketHasAllApproved->isNotEmpty()) {
            return $ticketQuery->where(fn($nonSpecialProjQuery) => $nonSpecialProjQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray()));
        } else {
            return $ticketQuery->where(fn($withSpecialProjQuery) => $withSpecialProjQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
                ->where(fn($nonSpecialProjQuery) => $nonSpecialProjQuery->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                    ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray()));
        }

    }

    public function serviceDeptAdminGetOverdueTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::OVERDUE)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }

    public function serviceDeptAdminGetClosedTickets()
    {
        return Ticket::where(fn($statusQuery) => $statusQuery->where('status_id', Status::CLOSED)->where('approval_status', ApprovalStatusEnum::APPROVED))
            ->where(fn($byUserQuery) => $byUserQuery->withWhereHas('user.branches', fn($query) => $query->orWhereIn('branches.id', auth()->user()->branches->pluck('id')->toArray()))
                ->withWhereHas('user.buDepartments', fn($query) => $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first())))
            ->orderByDesc('created_at')->get();
    }
}
