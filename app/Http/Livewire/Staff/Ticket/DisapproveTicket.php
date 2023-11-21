<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\Approver\StoreDisapproveTicketRequest;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Reason;
use App\Models\Status;
use App\Models\Ticket;
use App\Notifications\ServiceDepartmentAdmin\DisapprovedTicketNotification;
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
        return (new StoreDisapproveTicketRequest())->rules();
    }

    private function actionOnSubmit()
    {
        sleep(1);
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
                    'description' => $this->reasonDescription
                ]);

                $reason->ticket()->where('id', $this->ticket->id)->update([
                    'status_id' => Status::DISAPPROVED,
                    'approval_status' => ApprovalStatus::DISAPPROVED
                ]);

                Notification::send($this->ticket->user, new DisapprovedTicketNotification($this->ticket));
                ActivityLog::make($this->ticket->id, 'disapproved the ticket');
            });

            $this->actionOnSubmit();
            flash()->addSuccess('Ticket has been approved');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Ooos, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.disapprove-ticket');
    }
}