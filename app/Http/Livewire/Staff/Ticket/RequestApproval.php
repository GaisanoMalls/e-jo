<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\IctRecommendation;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
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
            IctRecommendation::create([
                'ticket_id' => $this->ticket->id,
                'approver_id' => $this->recommendationApprover,
                'is_requesting_ict_approval' => true
            ]);
            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error while sending recommendation request.', [$e->getLine()]);
        }
    }

    private function actionOnSubmit()
    {
        $this->emit('loadTicketActions');
        $this->reset('recommendationApprover');
        $this->dispatchBrowserEvent('close-request-recommendation-approval-modal');
    }

    public function render()
    {
        return view('livewire.staff.ticket.request-approval');
    }
}
