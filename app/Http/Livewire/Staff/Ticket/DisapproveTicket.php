<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Requests\Approver\StoreDisapproveTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Reason;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class DisapproveTicket extends Component
{
    public Ticket $ticket;

    public $reasonDescription;

    public function rules()
    {
        return(new StoreDisapproveTicketRequest())->rules();
    }

    private function actionOnSubmit()
    {
        $this->reset('reasonDescription');
        $this->emit('loadTicketTags');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadTicketActions');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadReplyButtonHeader');
        $this->emit('loadDisapprovalReason');
        $this->emit('loadDropdownApprovalButton');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadTicketStatusButtonHeader');
        $this->emit('loadClarificationButtonHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
    }

    public function disapproveTicket(): void
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $reason = Reason::create([
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->reasonDescription,
                ]);

                $reason->ticket()->where('id', $this->ticket->id)->update([
                    'status_id' => Status::DISAPPROVED,
                    'approval_status' => ApprovalStatusEnum::DISAPPROVED,
                ]);

                // Retrieve the service department administrator responsible for approving the ticket. For notification use only
                $serviceDepartmentAdmin = User::with('profile')->where('id', auth()->user()->id)->role(Role::SERVICE_DEPARTMENT_ADMIN)->first();

                Notification::send(
                    $this->ticket->user,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Disapproved Ticket {$this->ticket->ticket_number}",
                        message: "{$serviceDepartmentAdmin->profile->getFullName()} disapproved your ticket"
                    )
                );

                ActivityLog::make($this->ticket->id, 'disapproved the ticket');
            });

            $this->actionOnSubmit();
            noty()->addSuccess('Ticket has been approved');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.disapprove-ticket');
    }
}