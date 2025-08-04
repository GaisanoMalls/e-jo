<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Requests\Approver\Costing\ReasonOfDisapprovalRequest;
use App\Http\Traits\AppErrorLog;
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
use Livewire\Component;

class TicketCosting extends Component
{
    use Utils;

    public Ticket $ticket;
    public ?string $reasonOfDisapproval = null;

    protected $listeners = ['loadApproverTicketCosting' => '$refresh'];

    public function rules()
    {
        return (new ReasonOfDisapprovalRequest())->rules();
    }

    public function messages()
    {
        return (new ReasonOfDisapprovalRequest())->messages();
    }

    /**
     * Performs cleanup actions after a disapproval form submission.
     *
     * Resets the disapproval reason field and closes the modal window.
     * This ensures a clean state for subsequent disapproval actions.
     *
     * @return void
     * @dispatches close-modal Browser event
     */
    private function actionOnSubmit()
    {
        $this->reset('reasonOfDisapproval');
        $this->dispatchBrowserEvent('close-modal');
    }

    /**
     * Checks if the authenticated user is the second approver for special project costing.
     *
     * Verifies three conditions:
     * 1. The authenticated user matches the provided approver ID
     * 2. The user has general approver privileges
     * 3. The user is specifically listed as the FPM COO approver in the special project amount approval records
     *    for the current ticket (checked via JSON field)
     *
     * @param int $approverId The approver ID to validate against the current user
     * @return bool Returns true if:
     *              - Current user matches the provided approver ID
     *              - User has approver role
     *              - User is designated as FPM COO approver for this ticket's special project
     *             Returns false otherwise
     *
     * @uses \App\Models\SpecialProjectAmountApproval For approval records check
     * @uses JSON field query (whereJsonContains) for approver verification
     */
    public function isSpecialProjectCostingApprover2(int $approverId)
    {
        return auth()->user()->id === $approverId
            && auth()->user()->isApprover()
            && SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
                ->whereJsonContains('fpm_coo_approver->approver_id', $approverId)
                ->exists();
    }

    /**
     * Processes second-level approval for special project costing.
     *
     * Handles the FPM COO approval workflow for ticket costing by:
     * 1. Verifying user has approval permission via isSpecialProjectCostingApprover2()
     * 2. Checking prerequisite conditions:
     *    - First approval is completed (isDoneCostingApproval1)
     *    - COO approval is required (isCostingAmountNeedCOOApproval)
     * 3. In a database transaction:
     *    - Updates ticket status to APPROVED
     *    - Marks FPM COO approval in SpecialProjectAmountApproval
     *    - Records approval timestamp
     *    - Creates ApprovedCosting record
     * 4. Provides user feedback via notifications
     * 5. Handles errors gracefully with logging
     *
     * @return void
     * @throws \Exception On database errors (handled internally)
     *
     * @uses \App\Models\SpecialProjectAmountApproval For approval tracking
     * @uses \App\Models\ApprovedCosting To record approved costing
     * @uses \App\Models\Status For APPROVED status
     * @uses Carbon For timestamp management
     * @uses noty() For user notifications
     *
     * @fires loadApproverTicketCosting After successful approval
     * @emits success/error notifications Via noty()
     */
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
                noty()->addWarning("Sorry, You don't have permission to approve the costing.");
            }

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    /**
     * Processes second-level disapproval for special project costing.
     *
     * Handles the FPM COO disapproval workflow by:
     * 1. Validating the disapproval reason input
     * 2. Verifying user has disapproval permission via isSpecialProjectCostingApprover2()
     * 3. In a database transaction:
     *    - Updates service department admin approval status to false
     *    - Clears approval date
     *    - Creates DisapprovedCosting record with reason and amount
     * 4. Performs post-submission cleanup and redirection
     * 5. Provides user feedback via notifications
     * 6. Handles errors gracefully with logging
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException If validation fails
     * @throws \Exception On database errors (handled internally)
     *
     * @uses \App\Models\SpecialProjectAmountApproval For approval tracking
     * @uses \App\Models\DisapprovedCosting To record disapproval
     * @uses Carbon For timestamp management
     * @uses noty() For user notifications
     *
     * @fires actionOnSubmit After successful processing
     * @redirects approver.tickets.costing_approval After completion
     * @emits warning/error notifications Via noty()
     */
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
                noty()->addWarning("Sorry, You have no permission to disapprove the costing.");
            }

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    /**
     * Checks if the second-level (FPM COO) costing approval has been granted.
     *
     * Verifies whether the special project amount approval record for the current ticket
     * has been approved at the FPM COO level by checking the JSON field containing
     * approval status.
     *
     * @return bool Returns true if:
     *              - A SpecialProjectAmountApproval record exists for the current ticket
     *              - The 'fpm_coo_approver->is_approved' JSON field is set to true
     *             Returns false otherwise
     *
     * @uses \App\Models\SpecialProjectAmountApproval For approval status tracking
     * @uses JSON field query (whereJsonContains) for approval status check
     */
    public function isCostingApproval2Approved()
    {
        return SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
            ->whereJsonContains('fpm_coo_approver->is_approved', true)->exists();
    }

    public function isCostingApproval2Disapproved()
    {
        return SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
            ->whereJsonContains('fpm_coo_approver->is_approved', false)->exists();
    }

    public function render()
    {
        return view('livewire.approver.ticket.ticket-costing');
    }
}
