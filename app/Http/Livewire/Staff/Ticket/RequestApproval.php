<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\RecommendationApprovalStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Mail\Staff\RecommendationRequestMail;
use App\Models\ActivityLog;
use App\Models\Recommendation;
use App\Models\RecommendationApprovalStatus;
use App\Models\RecommendationApprover;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Log;

class RequestApproval extends Component
{
    public Ticket $ticket;
    public Collection $recommendationApprovers;
    public array $levels = [1, 2, 3, 4, 5];
    public ?int $levelOfApproval = null;
    public array $level1Approvers = [];
    public array $level2Approvers = [];
    public array $level3Approvers = [];
    public array $level4Approvers = [];
    public array $level5Approvers = [];
    public ?string $reason = null;

    public function mount()
    {
        $this->recommendationApprovers = User::with(['profile', 'roles', 'buDepartments'])
            ->role([Role::APPROVER, Role::SERVICE_DEPARTMENT_ADMIN])
            ->get();
    }

    public function rules()
    {
        return [
            'levelOfApproval' => ['required'],
            'reason' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => 'Please state the reason'
        ];
    }

    public function updatedLevelOfApproval($value)
    {
        if ($value) {
            $this->dispatchBrowserEvent('load-recommendation-approvers', [
                'level' => $value,
                'recommendationApprovers' => $this->recommendationApprovers
            ]);
        }
    }

    public function sendRequestRecommendationApproval()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $recommendationApproverIds = array_unique(
                    array_merge(...array_values($this->recommendationLevelApprovers()))
                );

                $recommendationApprovers = User::whereIn('id', $recommendationApproverIds)
                    ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
                    ->get();

                $requesterServiceDeptAdmin = User::where('id', auth()->user()->id)
                    ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
                    ->first();

                if ($recommendationApprovers->isNotEmpty() || $requesterServiceDeptAdmin->exists()) {
                    $this->ticket->update(['status_id' => Status::OPEN]);

                    $recommendation = Recommendation::create([
                        'ticket_id' => $this->ticket->id,
                        'requested_by_sda_id' => $requesterServiceDeptAdmin->id,
                        'is_requesting_for_approval' => true,
                        'reason' => $this->reason,
                        'level_of_approval' => $this->levelOfApproval
                    ]);

                    RecommendationApprovalStatus::create(['recommendation_id' => $recommendation->id]);

                    foreach ($this->recommendationLevelApprovers() as $level => $approvers) {
                        foreach ($approvers as $approver) {
                            RecommendationApprover::create([
                                'recommendation_id' => $recommendation->id,
                                'approver_id' => $approver,
                                'level' => $level
                            ]);
                        }
                    }

                    // Mail::to($serviceDeptAdmin)->send(new RecommendationRequestMail(ticket: $this->ticket, recipient: $serviceDeptAdmin, agentRequester: $agentRequester));
                    $recommendationApprovers->each(function ($recommendationApprover) {
                        Notification::send(
                            $recommendationApprover,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (Approval Request)",
                                message: "You have a new ticket approval"
                            )
                        );
                    });

                    ActivityLog::make(ticket_id: $this->ticket->id, description: 'requested for approval');
                    return redirect()->route('staff.ticket.view_ticket', $this->ticket->id);
                }
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error while sending approval request.', [$e->getLine()]);
        }
    }

    private function recommendationLevelApprovers()
    {
        return array_filter([
            1 => array_map('intval', $this->level1Approvers),
            2 => array_map('intval', $this->level2Approvers),
            3 => array_map('intval', $this->level3Approvers),
            4 => array_map('intval', $this->level4Approvers),
            5 => array_map('intval', $this->level5Approvers),
        ], function ($approvers) {
            return !empty($approvers);
        });
    }

    public function render()
    {
        return view('livewire.staff.ticket.request-approval');
    }
}
