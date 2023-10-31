<?php

namespace App\Http\Traits;

use App\Http\Traits\Agent\Tickets as AgentTickets;
use App\Http\Traits\ServiceDepartmentAdmin\Tickets as ServiceDepartmentAdminTickets;
use App\Http\Traits\SysAdmin\Tickets as SysAdminTickets;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait TicketsByStaffWithSameTemplates
{
    use ServiceDepartmentAdminTickets, SysAdminTickets, AgentTickets;

    public function getTicketsToAssign(): Collection|array
    {
        return (auth()->user()->role_id === Role::SERVICE_DEPARTMENT_ADMIN)
            ? $this->serviceDeptAdminGetTicketsToAssign()
            : [];
    }

    public function getApprovedTickets(): Collection|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetApprovedTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetApprovedTickets(),
            default => [],
        };
    }

    public function getDisapprovedTickets(): Collection|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetDisapprovedTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetDisapprovedTickets(),
            default => [],
        };
    }

    public function getOpenTickets(): Collection|Builder|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetOpenTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetOpentTickets(),
            Role::AGENT => $this->agentGetOpenTickets(),
            default => [],
        };
    }

    public function getClaimedTickets(): Collection|Builder|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetClaimedTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetClaimedTickets(),
            Role::AGENT => $this->agentGetClaimedTickets(),
            default => [],
        };
    }

    public function getOnProcessTickets(): Collection|Builder|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetOnProcessTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetOnProcessTickets(),
            Role::AGENT => $this->agentGetOnProcessTickets(),
            default => [],
        };
    }

    public function getViewedTickets(): Collection|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetViewedTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetViewedTickets(),
            default => [],
        };
    }

    public function getOverdueTickets(): Collection|Builder|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetOverdueTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetOverdueTickets(),
            Role::AGENT => $this->agentGetOverdueTickets(),
            default => [],
        };
    }

    public function getClosedTickets(): Collection|Builder|array
    {
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => $this->sysAdminGetClosedTickets(),
            Role::SERVICE_DEPARTMENT_ADMIN => $this->serviceDeptAdminGetClosedTickets(),
            Role::AGENT => $this->agentGetClosedTickets(),
            default => [],
        };
    }
}
