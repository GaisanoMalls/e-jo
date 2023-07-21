<?php

namespace App\Http\Traits\ServiceDepartmentAdmin;

use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;

trait Tickets
{
    public function getApprovedTickets()
    {
        $approvedTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::APPROVED)
                ->orWhere('approval_status', ApprovalStatus::APPROVED)
                ->where(function ($query) {
                    $query->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $approvedTickets;
    }

    public function getOpentTickets()
    {
        $openTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::OPEN)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where(function ($query) {
                    $query->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $openTickets;
    }

    public function getOnProcessTickets()
    {
        $onProcessTickets = Ticket::where(function ($query) {
            $query->where('status_id', Status::ON_PROCESS)
                ->where('approval_status', ApprovalStatus::APPROVED)
                ->where(function ($query) {
                    $query->where('branch_id', auth()->user()->branch_id)
                        ->where('service_department_id', auth()->user()->service_department_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return $onProcessTickets;
    }
}