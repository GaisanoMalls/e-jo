<?php

namespace App\Http\Traits\Approver;

use App\Enums\ApprovalStatusEnum;
use App\Enums\RecommendationApprovalStatusEnum;
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
            ->where(function ($query) {
                $userId = auth()->user()->id;

                $query->orWhereHas('recommendations', function ($recommendation) use ($userId) {
                    $recommendation->whereHas('approvalStatus', function ($status) {
                        $status->where('approval_status', RecommendationApprovalStatusEnum::PENDING);
                    })
                    ->orWhereHas('approvers', function ($approver) use ($userId) {
                        $approver->where('recommendation_approvers.approver_id', $userId);
                    });
                })

                // Replace the original helpTopicApprover with logic that checks for level and approval
                ->orWhere(function ($subQuery) use ($userId) {
                    $subQuery->whereHas('ticketApprovals.helpTopicApprover', function ($q) use ($userId) {
                        $q->where('user_id', $userId)
                        ->where(function ($levelQuery) use ($userId) {
                            $levelQuery->where('level', 1)
                                ->orWhere(function ($level2Query) use ($userId) {
                                    $level2Query->where('level', 2)
                                        ->whereExists(function ($existsQuery) {
                                            $existsQuery->selectRaw(1)
                                                ->from('ticket_approval as ta1')
                                                ->join('help_topic_approvers as hta1', 'ta1.help_topic_approver_id', '=', 'hta1.id')
                                                ->whereColumn('ta1.ticket_id', 'tickets.id')
                                                ->whereColumn('hta1.help_topic_id', 'help_topic_approvers.help_topic_id')
                                                ->where('hta1.level', 1)
                                                ->where('ta1.is_approved', 1);
                                        });
                                });
                        });
                    });
                });
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
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getViewedTickets()
    {
        return Ticket::where('status_id', Status::VIEWED)
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
            })
            ->whereHas('ticketApprovals', function ($approval) {
                $userId = auth()->user()->id;

                $approval->where('is_approved', false)
                    ->whereHas('helpTopicApprover', function ($approver) use ($userId) {
                        $approver->where('user_id', $userId)
                            ->where(function ($levelQuery) {
                                $levelQuery->where('level', 1)
                                    ->orWhere(function ($level2Query) {
                                        $level2Query->where('level', 2)
                                            ->whereExists(function ($existsQuery) {
                                                $existsQuery->selectRaw(1)
                                                    ->from('ticket_approval as ta1')
                                                    ->join('help_topic_approvers as hta1', 'ta1.help_topic_approver_id', '=', 'hta1.id')
                                                    ->whereColumn('ta1.ticket_id', 'ticket_approval.ticket_id')
                                                    ->whereColumn('hta1.help_topic_id', 'help_topic_approvers.help_topic_id')
                                                    ->where('hta1.level', 1)
                                                    ->where('ta1.is_approved', 1);
                                            });
                                    });
                            });
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
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
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
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getClaimedTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->whereNotNull('agent_id')
                    ->where([
                        ['status_id', Status::CLAIMED],
                        ['approval_status', ApprovalStatusEnum::APPROVED]
                    ]);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getOverdueTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where([
                    ['status_id', Status::OVERDUE],
                    ['approval_status', ApprovalStatusEnum::APPROVED]
                ]);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
            })
            ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                $approver->where('user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getClosedTickets()
    {
        return Ticket::whereHas('user', fn($user) => $user->withTrashed())
            ->where(function ($statusQuery) {
                $statusQuery->where([
                    ['status_id', Status::CLOSED],
                    ['approval_status', ApprovalStatusEnum::APPROVED]
                ]);
            })
            ->whereHas('user.buDepartments', function ($department) {
                $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id'));
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
