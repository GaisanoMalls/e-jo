<?php

namespace App\Http\Traits;

use App\Http\Traits\Agent\Tickets as AgentTickets;
use App\Http\Traits\ServiceDepartmentAdmin\Tickets as ServiceDepartmentAdminTickets;
use App\Http\Traits\SysAdmin\Tickets as SysAdminTickets;

trait TicketsByStaffWithSameTemplates
{
    use ServiceDepartmentAdminTickets, SysAdminTickets, AgentTickets;

    public function getOpenTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetOpenTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetOpentTickets(),
            auth()->user()->isAgent() => $this->agentGetOpenTickets(),
            default => collect(),
        };
    }

    public function getApprovedTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetApprovedTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetApprovedTickets(),
            default => collect(),
        };
    }

    public function getDisapprovedTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetDisapprovedTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetDisapprovedTickets(),
            default => collect(),
        };
    }

    public function getClaimedTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetClaimedTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetClaimedTickets(),
            auth()->user()->isAgent() => $this->agentGetClaimedTickets(),
            default => [],
        };
    }

    public function getOnProcessTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetOnProcessTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetOnProcessTickets(),
            auth()->user()->isAgent() => $this->agentGetOnProcessTickets(),
            default => collect(),
        };
    }

    public function getViewedTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetViewedTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetViewedTickets(),
            default => collect(),
        };
    }

    public function getOverdueTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetOverdueTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetOverdueTickets(),
            auth()->user()->isAgent() => $this->agentGetOverdueTickets(),
            default => collect(),
        };
    }

    public function getClosedTickets()
    {
        return match (true) {
            auth()->user()->isSystemAdmin() => $this->sysAdminGetClosedTickets(),
            auth()->user()->isServiceDepartmentAdmin() => $this->serviceDeptAdminGetClosedTickets(),
            auth()->user()->isAgent() => $this->agentGetClosedTickets(),
            default => collect(),
        };
    }
}
