<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Mail\Requester\TicketCreatedMail;
use App\Mail\Staff\ApprovedTicketMail;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Requester\TicketCreatedNotification;
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

    /**
     * Perform livewire events upon form submission.
     */
    private function actionOnSubmit()
    {
        $this->emit('loadSlaTimer');
        $this->emit('loadTicketTags');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadTicketActions');
        $this->emit('loadLevelOfApproval');
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

    public function approveTicket()
    {
        try {
            DB::transaction(function () {
                // Update the ticket status if approved.
                $this->ticket->update([
                    'status_id' => Status::APPROVED,
                    'approval_status' => ApprovalStatus::APPROVED,
                ]);

                // Get the agents with specific conditions.
                $agents = User::withWhereHas('teams', fn($query) => $query->where('teams.id', $this->ticket->team_id))
                    ->whereHas('branches', fn($query) => $query->where('branches.id', $this->ticket->branch_id))
                    ->whereHas('serviceDepartments', fn($query) => $query->where('service_departments.id', $this->ticket->service_department_id))->get();

                // Notify agents through email and app based notification.
                foreach ($agents as $agent) {
                    Mail::to($agent)->send(new ApprovedTicketMail($this->ticket, $agent));
                    Notification::send($agent, new ApprovedTicketForAgentNotification($this->ticket));
                }

                // Notify the ticket sender/requester.
                Notification::send($this->ticket->user, new ApprovedTicketForRequesterNotification($this->ticket));
                ActivityLog::make($this->ticket->id, 'approved the ticket');
            });

            $this->actionOnSubmit();
            noty()->addSuccess('Ticket has been approved');

        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.approve-ticket');
    }
}
