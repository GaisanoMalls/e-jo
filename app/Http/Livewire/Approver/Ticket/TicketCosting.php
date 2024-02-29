<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Requests\Approver\Costing\ReasonOfDisapprovalRequest;
use App\Http\Traits\Utils;
use App\Models\ApprovedCosting;
use App\Models\DisapprovedCosting;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TicketCosting extends Component
{
    use Utils;

    public Ticket $ticket;
    public string $reasonOfDisapproval = '';

    protected $listeners = ['loadApproverTicketCosting' => '$refresh'];

    public function rules()
    {
        return(new ReasonOfDisapprovalRequest())->rules();
    }

    public function messages()
    {
        return(new ReasonOfDisapprovalRequest())->messages();
    }

    private function actionOnSubmit()
    {
        $this->reset('reasonOfDisapproval');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function isSpecialProjectCostingApprover2(int $approverId)
    {
        return auth()->user()->id === $approverId
            && auth()->user()->hasRole(Role::APPROVER)
            && SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
                ->whereJsonContains('fpm_coo_approver->approver_id', $approverId)
                ->exists();
    }

    public function approveCostingApproval2()
    {
        try {
            $costingApprover2Id = User::role(Role::APPROVER)->where('id', auth()->user()->id)->value('id');

            if ($this->isSpecialProjectCostingApprover2($costingApprover2Id)) {
                if ($this->isDoneCostingApproval1($this->ticket) && $this->isCostingAmountNeedCOOApproval($this->ticket)) {
                    DB::transaction(function () {
                        // Change the ticket status to Approved
                        $this->ticket->status_id = Status::APPROVED;

                        // Perform the update first
                        SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
                            ->update([
                                'fpm_coo_approver->is_approved' => true,
                                'fpm_coo_approver->date_approved' => Carbon::now(),
                                'is_done' => true
                            ]);

                        // Retrieve the updated instance
                        $specialProjectAmountApproval = SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)->first();
                        if ($specialProjectAmountApproval) {
                            ApprovedCosting::create([
                                'special_project_amount_approval_id' => $specialProjectAmountApproval->id,
                                'approved_date' => Carbon::now(),
                            ]);
                        } else {
                            noty()->addError('Amount approval for special project not found.');
                        }
                    });
                    $this->emit('loadApproverTicketCosting');
                    noty()->addSuccess('Ticket costing is approved.');
                }
            } else {
                noty()->addWarning("Sorry, You don't have permission to approve the costing");
            }

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function disapproveCostingApproval2()
    {
        $this->validate();

        try {
            $costingApprover2Id = User::role(Role::APPROVER)->where('id', auth()->user()->id)->value('id');

            if ($this->isSpecialProjectCostingApprover2($costingApprover2Id)) {
                DB::transaction(function () {
                    // Perform the update first
                    SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
                        ->update([
                            'service_department_admin_approver->is_approved' => false,
                            'service_department_admin_approver->date_approved' => null,
                        ]);

                    // Retrieve the updated instance
                    $specialProjectAmountApproval = SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)->first();
                    if ($specialProjectAmountApproval) {
                        DisapprovedCosting::create([
                            'special_project_amount_approval_id' => $specialProjectAmountApproval->id,
                            'reason' => $this->reasonOfDisapproval,
                            'amount' => $specialProjectAmountApproval->ticket->helpTopic->specialProject->amount,
                            'disapproved_date' => Carbon::now(),
                        ]);
                    } else {
                        noty()->addError('Amount approval for special project not found.');
                    }
                });

                $this->actionOnSubmit();
                $this->redirectRoute('approver.tickets.costing_approval');

            } else {
                noty()->addWarning("Sorry, You have no permission to disapprove the costing");
            }

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
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
