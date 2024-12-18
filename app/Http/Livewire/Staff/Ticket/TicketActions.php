<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Models\Recommendation;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;

class TicketActions extends Component
{
    public Ticket $ticket;
    public ?Recommendation $recommendationApprover = null;

    protected $listeners = ['loadTicketActions' => '$refresh'];

    public function getCurrentTeamOrAgent()
    {
        if (!is_null($this->ticket->team_id)) {
            $this->dispatchBrowserEvent('get-current-team-or-agent', ['ticket' => $this->ticket]);
        }
    }

    /**
     * Verify whether the business unit of the ticket requester matches the business unit of the Service Department Admin.
     */
    public function isRequesterServiceDeptAdmin()
    {
        return User::where('id', auth()->user()->id)
            ->withWhereHas('branches', function ($branch) {
                $branch->whereIn('branches.id', $this->ticket->user->branches->pluck('id')->toArray());
            })
            ->withWhereHas('buDepartments', function ($department) {
                $department->whereIn('departments.id', $this->ticket->user->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
            ->exists();
    }

    public function isTicketRecommendationIsApproved()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['approval_status', RecommendationApprovalStatusEnum::APPROVED]
        ])->exists();
    }

    public function render()
    {
        $this->recommendationApprover = Recommendation::where('ticket_id', $this->ticket->id)->first();

        return view('livewire.staff.ticket.ticket-actions');
    }
}