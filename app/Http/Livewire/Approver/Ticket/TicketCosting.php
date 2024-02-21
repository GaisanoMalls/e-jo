<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class TicketCosting extends Component
{
    use Utils;

    public Ticket $ticket;

    protected $listeners = ['loadApproverTicketCosting' => '$refresh'];

    public function isSpecialProjectCostingApprover2(int $approverId)
    {
        return auth()->user()->id === $approverId
            && auth()->user()->hasRole(Role::APPROVER)
            && SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)->whereJsonContains('fpm_coo_approver->approver_id', $approverId)->exists();
    }

    public function approveCostingApproval2()
    {
        $costingApprover1Id = User::role(Role::APPROVER)->where('id', auth()->user()->id)->value('id');

        if ($this->isSpecialProjectCostingApprover2($costingApprover1Id)) {
            SpecialProjectAmountApproval::where([
                ['ticket_id', $this->ticket->id],
                ['service_department_admin_approver->is_approved', true],
                ['fpm_coo_approver->is_approved', false]
            ])->whereNotNull(['service_department_admin_approver->approver_id', 'service_department_admin_approver->date_approved'])
                ->update([
                    'fpm_coo_approver->is_approved' => true,
                    'fpm_coo_approver->date_approved' => Carbon::now(),
                    'is_done' => true
                ]);

            $this->emit('loadApproverTicketCosting');
            noty()->addSuccess('Ticket costing is approved.');
        } else {
            noty()->addWarning("Sorry, You don't have permission to approve the costing");
        }
    }

    public function isCostingApproval2Approved()
    {
        return SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
            ->whereJsonContains('fpm_coo_approver->is_approved', true)->exists();
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-costing');
    }
}
