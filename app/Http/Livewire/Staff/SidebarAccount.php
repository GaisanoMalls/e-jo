<?php

namespace App\Http\Livewire\Staff;

use App\Http\Traits\ServiceDepartmentAdmin\Tickets as ServiceDepartmentAdminTickets;
use App\Http\Traits\Agent\Tickets as AgentTickets;
use App\Models\PriorityLevel;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class SidebarAccount extends Component
{
    use ServiceDepartmentAdminTickets, AgentTickets;

    public Collection $priorityLevels;
    public function mount()
    {
        $this->priorityLevels = PriorityLevel::all();
    }

    private function countServiceDeptAdminTicketsByPriorityLevel(PriorityLevel $priorityLevel)
    {
        return $this->serviceDeptAdminGetOpentTickets()
            ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
            ->where('priority_level_id', $priorityLevel->id)
            ->count()
            + $this->serviceDeptAdminGetViewedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->serviceDeptAdminGetApprovedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->serviceDeptAdminGetDisapprovedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->serviceDeptAdminGetClaimedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->serviceDeptAdminGetOnProcessTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->serviceDeptAdminGetClosedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->serviceDeptAdminGetOverdueTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count();
    }

    private function countAgentTicketsByPriorityLevel(PriorityLevel $priorityLevel)
    {
        return $this->agentGetOpenTickets()
            ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
            ->where('priority_level_id', $priorityLevel->id)
            ->count()
            + $this->agentGetClaimedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->agentGetOnProcessTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->agentGetOverdueTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count()
            + $this->agentGetClosedTickets()
                ->whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count();
    }

    public function countTicketsByPriorityLevel(PriorityLevel $priorityLevel)
    {
        $currentUser = User::find(auth()->user()->id);

        if ($currentUser->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)) {
            $ticketCount = $this->countServiceDeptAdminTicketsByPriorityLevel($priorityLevel);
        }

        if ($currentUser->hasRole(Role::AGENT)) {
            $ticketCount = $this->countAgentTicketsByPriorityLevel($priorityLevel);
        }

        if ($currentUser->hasRole(Role::SYSTEM_ADMIN)) {
            $ticketCount = Ticket::whereNotIn('status_id', [Status::CLOSED, Status::OVERDUE, Status::DISAPPROVED])
                ->where('priority_level_id', $priorityLevel->id)
                ->count();
        }

        return $ticketCount;
    }

    public function render()
    {
        return view('livewire.staff.sidebar-account');
    }
}