<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\ServiceDepartmentAdmin\ApprovedTicketForAgentNotification;
use App\Notifications\ServiceDepartmentAdmin\ApprovedTicketForRequesterNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ApproveTicket extends Component
{
    public Ticket $ticket;

    private function actionOnSubmit(): void
    {
        sleep(1);
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
    }

    public function approveTicket(): void
    {
        try {
            DB::transaction(function () {
                $this->ticket->update([
                    'status_id' => Status::APPROVED,
                    'approval_status' => ApprovalStatus::APPROVED
                ]);

                $agents = User::withWhereHas('teams', fn($query) => $query->where('teams.id', $this->ticket->team_id))
                    ->whereHas('branch', fn($query) => $query->where('branch_id', $this->ticket->branch_id))
                    ->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->ticket->service_department_id))->get();

                foreach ($agents as $agent) {
                    Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                    Notification::send($agent, new ApprovedTicketForAgentNotification($this->ticket));
                }

                Notification::send($this->ticket->user, new ApprovedTicketForRequesterNotification($this->ticket));
                ActivityLog::make($this->ticket->id, 'approved the ticket');
            });

            $this->actionOnSubmit();
            flash()->addSuccess('Ticket has been approved');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.approve-ticket');
    }
}
