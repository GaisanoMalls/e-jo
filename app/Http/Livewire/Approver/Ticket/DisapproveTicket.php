<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Requests\Approver\StoreDisapproveTicketRequest;
use App\Http\Traits\TicketApprovalLevel;
use App\Models\ActivityLog;
use App\Models\Reason;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Traits\AppErrorLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DisapproveTicket extends Component
{
    use TicketApprovalLevel;

    public Ticket $ticket;
    public ?string $disapproveReason = null;

    public function rules()
    {
        return (new StoreDisapproveTicketRequest())->rules();
    }

    public function actionOnSubmit()
    {
        return redirect()->route('approver.ticket.view_ticket_details', $this->ticket->id);
    }

    public function disapproveTicket()
    {
        try {
            DB::transaction(function () {
                if ($this->isPriorLevelApproved($this->ticket)) {
                    $reason = Reason::create([
                        'ticket_id' => $this->ticket->id,
                        'description' => $this->disapproveReason,
                    ]);

                    $reason->ticket()->where('id', $this->ticket->id)->update([
                        'status_id' => Status::DISAPPROVED,
                        'approval_status' => ApprovalStatusEnum::DISAPPROVED,
                    ]);

                    $approver = User::with('profile')
                        ->where('id', auth()->user()->id)
                        ->role(Role::APPROVER)
                        ->firstOrFail();

                    Notification::send(
                        $this->ticket->user,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Disapproved)",
                            message: "{$approver->profile->getFullName} disapproved your ticket"
                        )
                    );

                    ActivityLog::make(ticket_id: $this->ticket->id, description: 'disapproved the ticket');
                    $this->actionOnSubmit();
                    noty()->addSuccess('Ticket has been disapproved');
                } else {
                    noty()->addInfo("Prior levels must be approved before approving this level.");
                    return;
                }
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.disapprove-ticket');
    }
}
