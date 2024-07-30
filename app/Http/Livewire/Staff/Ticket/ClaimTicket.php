<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClaimTicket extends Component
{
    public Ticket $ticket;

    protected $listeners = ['loadClaimTicket' => '$refresh'];

    private function actionOnSubmit()
    {
        $this->emit('loadTicketLogs');
        $this->emit('loadClaimTicket');
        $this->emit('loadTicketActions');
        $this->emit('loadTicketDetails');
        $this->emit('loadLevelOfApproval');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadCostingButtonHeader');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
    }

    public function claimTicket()
    {
        try {
            DB::transaction(function () {
                if (!is_null($this->ticket->agent_id)) {
                    noty()->addError('Ticket has already been claimed by another agent. Select another ticket to claim.');
                    return;
                }

                if (!is_null($this->ticket->isSpecialProject())) {
                    $agent = User::where('id', auth()->user()->id)
                        ->withWhereHas('teams', fn($team) => $team->whereIn('teams.id', $this->ticket->teams->pluck('id')->toArray()))
                        ->first();

                    // Pratially disabled
                    // if (is_null($agent)) {
                    //     if ($this->ticket->teams()->count() === 0) {
                    //         noty()->addWarning("Unable to claim this ticket. Please wait for the service dept admin to assign this ticket directly to you or to your team.");
                    //     } else {
                    //         noty()->addWarning("Unable to claim this ticket since you're not part of these teams: {$this->ticket->getTeams()}");
                    //     }
                    //     return;
                    // }
                }

                $this->ticket->update([
                    'agent_id' => auth()->user()->id,
                    'status_id' => Status::CLAIMED,
                ]);

                $this->ticket->teams()->attach($this->ticket->agent->teams->pluck('id')->toArray());
                ActivityLog::make($this->ticket->id, 'claimed the ticket');

                $this->actionOnSubmit();
                noty()->addSuccess("You have claimed the ticket - {$this->ticket->ticket_number}.");
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function isDoneFirstLevelApproval()
    {
        return TicketApproval::where([
            ['ticket_id', $this->ticket->id],
            ['approval_1->level_1_approver->is_approved', true],
            ['approval_1->level_2_approver->is_approved', true],
        ])->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.claim-ticket');
    }
}