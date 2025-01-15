<?php

namespace App\Http\Traits;

use App\Http\Traits\Agent\Tickets as AgentTickets;
use App\Http\Traits\ServiceDepartmentAdmin\Tickets as ServiceDepartmentAdminTickets;
use App\Http\Traits\SysAdmin\Tickets as SysAdminTickets;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

trait TicketsByStaffWithSameTemplates
{
    use ServiceDepartmentAdminTickets, SysAdminTickets, AgentTickets;

    public function getOpenTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetOpenTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetOpentTickets(),
            Auth::user()->hasRole(Role::AGENT) => $this->agentGetOpenTickets(),
            default => [],
        };
    }

    public function getApprovedTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetApprovedTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetApprovedTickets(),
            default => [],
        };
    }

    public function getDisapprovedTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetDisapprovedTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetDisapprovedTickets(),
            default => [],
        };
    }

    public function getClaimedTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetClaimedTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetClaimedTickets(),
            Auth::user()->hasRole(Role::AGENT) => $this->agentGetClaimedTickets(),
            default => [],
        };
    }

    public function getOnProcessTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetOnProcessTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetOnProcessTickets(),
            Auth::user()->hasRole(Role::AGENT) => $this->agentGetOnProcessTickets(),
            default => [],
        };
    }

    public function getViewedTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetViewedTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetViewedTickets(),
            default => [],
        };
    }

    public function getOverdueTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetOverdueTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetOverdueTickets(),
            Auth::user()->hasRole(Role::AGENT) => $this->agentGetOverdueTickets(),
            default => [],
        };
    }

    public function getClosedTickets()
    {
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => $this->sysAdminGetClosedTickets(),
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => $this->serviceDeptAdminGetClosedTickets(),
            Auth::user()->hasRole(Role::AGENT) => $this->agentGetClosedTickets(),
            default => [],
        };
    }
}
