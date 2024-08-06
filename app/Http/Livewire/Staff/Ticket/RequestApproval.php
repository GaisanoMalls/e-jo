<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Mail\Staff\RecommendationRequestMail;
use App\Models\ActivityLog;
use App\Models\IctRecommendation;
use App\Models\Role;
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
    public ?int $recommendationApprover = null;

    public function mount()
    {
        $this->recommendationApprovers = User::with(['profile', 'roles', 'buDepartments'])->role(Role::SERVICE_DEPARTMENT_ADMIN)->get();
    }

    public function rules()
    {
        return [
            'recommendationApprover' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'recommendationApprover.required' => 'Approver field is required.'
        ];
    }

    public function sendRequestRecommendationApproval()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $serviceDeptAdmin = User::findOrFail($this->recommendationApprover)
                    ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
                    ->first();

                $requesterServiceDeptAdmin = auth()->user()
                    ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
                    ->first();

                if ($serviceDeptAdmin && $requesterServiceDeptAdmin) {
                    IctRecommendation::create([
                        'ticket_id' => $this->ticket->id,
                        'approver_id' => $serviceDeptAdmin->id,
                        'requested_by_sda_id' => $requesterServiceDeptAdmin->id,
                        'is_requesting_ict_approval' => true
                    ]);

                    // Mail::to($serviceDeptAdmin)->send(new RecommendationRequestMail(ticket: $this->ticket, recipient: $serviceDeptAdmin, agentRequester: $agentRequester));
                    Notification::send(
                        $serviceDeptAdmin,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Request for recommendation approval ({$this->ticket->ticket_number}})",
                            message: "You have a new recommendation approval"
                        )
                    );

                    ActivityLog::make($this->ticket->id, 'created a recommendation');
                    $this->actionOnSubmit();
                }
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error while sending recommendation request.', [$e->getLine()]);
        }
    }

    private function actionOnSubmit()
    {
        $this->emit('loadTicketActions');
        $this->emit('refreshCustomForm');
        $this->emit('loadTicketLogs');
        $this->reset('recommendationApprover');
        $this->dispatchBrowserEvent('close-request-recommendation-approval-modal');
    }

    public function render()
    {
        return view('livewire.staff.ticket.request-approval');
    }
}
