<?php

namespace App\Http\Traits;

use App\Http\Traits\Agent\Tickets as AgentTickets;
use App\Http\Traits\ServiceDepartmentAdmin\Tickets as ServiceDepartmentAdminTickets;
use App\Http\Traits\SysAdmin\Tickets as SysAdminTickets;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

trait TicketsByStaffWithSameTemplates
{
    use ServiceDepartmentAdminTickets, SysAdminTickets, AgentTickets;

    public function getApprovedTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $approvedTickets = $this->sysAdminGetApprovedTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $approvedTickets = $this->serviceDeptAdminGetApprovedTickets();
                break;
            default:
                $approvedTickets = null;
        }

        return $approvedTickets;
    }

    public function getDisapprovedTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $approvedTickets = $this->sysAdminGetDisapprovedTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $approvedTickets = $this->serviceDeptAdminGetDisapprovedTickets();
                break;
            default:
                $approvedTickets = null;
        }

        return $approvedTickets;
    }

    public function getOpenTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $openTickets = $this->sysAdminGetOpenTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $openTickets = $this->serviceDeptAdminGetOpentTickets();
                break;
            case Role::AGENT:
                $openTickets = $this->agentGetOpenTickets();
                break;
            default:
                $openTickets = null;
        }

        return $openTickets;
    }

    public function getClaimedTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $claimedTickets = $this->sysAdminGetClaimedTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $claimedTickets = $this->serviceDeptAdminGetClaimedTickets();
                break;
            case Role::AGENT:
                $claimedTickets = $this->agentGetClaimedTickets();
                break;
            default:
                $claimedTickets = null;
        }

        return $claimedTickets;
    }

    public function getOnProcessTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $onProcessTickets = $this->sysAdminGetOnProcessTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $onProcessTickets = $this->serviceDeptAdminGetOnProcessTickets();
                break;
            case Role::AGENT:
                $onProcessTickets = $this->agentGetOnProcessTickets();
                break;
            default:
                $onProcessTickets = null;
        }

        return $onProcessTickets;
    }

    public function getViewedTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $viewedTickets = $this->sysAdminGetViewedTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $viewedTickets = $this->serviceDeptAdminGetViewedTickets();
                break;
            default:
                $viewedTickets = null;
        }

        return $viewedTickets;
    }

    public function getOverdueTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $overdueTickets = $this->sysAdminGetOverdueTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $overdueTickets = $this->serviceDeptAdminGetOverdueTickets();
                break;
            case Role::AGENT:
                $overdueTickets = $this->agentGetOverdueTickets();
                break;
            default:
                $overdueTickets = null;
        }

        return $overdueTickets;
    }

    public function getClosedTickets()
    {
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $closedTickets = $this->sysAdminGetClosedTickets();
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $closedTickets = $this->serviceDeptAdminGetClosedTickets();
                break;
            case Role::AGENT:
                $closedTickets = $this->agentGetClosedTickets();
                break;
            default:
                $closedTickets = null;
        }

        return $closedTickets;
    }
}