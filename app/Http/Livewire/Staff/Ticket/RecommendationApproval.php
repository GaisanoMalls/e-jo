<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\Recommendation;
use App\Models\RecommendationApprover;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RecommendationApproval extends Component
{
    public ?Ticket $ticket;
    public Collection $recommendationApprovers;


    protected $listeners = ['loadRecommendationApproval' > '$refresh'];

    public function approveRecommendation()
    {
        try {
            DB::transaction(function () {
                Recommendation::where('ticket_id', $this->ticket->id)->update(['is_approved' => true]);
                $this->emit('refreshCustomForm');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error while sending recommendation request.', [$e->getLine()]);
        }
    }

    public function isTicketRecommendationIsApproved()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true]
        ])->exists();
    }

    public function isRecommendationRequested()
    {
        return Recommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_requesting_ict_approval', true],
        ])->exists();
    }

    public function recommendationApprovers()
    {
        return;
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

    public function render()
    {
        $this->recommendationApprovers = RecommendationApprover::with('approver.profile')
            ->withWhereHas('approvalLevel.recommendation', function ($recommendation) {
                $recommendation->where('ticket_id', $this->ticket->id);
            })->get();

        return view('livewire.staff.ticket.recommendation-approval');
    }
}
