<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\SpecialProjectStatusEnum;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\PurchasingTeam;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\TicketCosting as Costing;
use App\Models\TicketCostingFile;
use App\Models\TicketCostingPRFile;
use App\Models\TicketSpecialProjectStatus;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class TicketCosting extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $editingFieldId;
    public $amount;
    public $uploadFileCostingCount = 0;
    public $newCostingFiles = [];
    public $additionalCostingFiles = [];
    public $allowedExtensions = ['pdf'];
    protected $listeners = ['loadServiceDeptAdminTicketCosting' => '$refresh'];

    public function updatedNewCostingFiles()
    {
        $this->validate([
            'newCostingFiles.*' => [
                'required',
                File::types($this->allowedExtensions)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function updatedAdditionalCostingFiles()
    {
        $this->validate([
            'additionalCostingFiles.*' => [
                'required',
                File::types($this->allowedExtensions)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function toggleEditCostingAmount(Costing $costing)
    {
        $ticketCosting = $costing->where('ticket_id', $this->ticket->id)->select('id', 'amount')->first();

        if ($ticketCosting) {
            $this->editingFieldId = $ticketCosting->id == $this->editingFieldId ? null : $ticketCosting->id;
            $this->amount = $ticketCosting->amount;
        } else {
            noty()->addError('Ticket costing not found.');
        }

        $this->resetValidation();
    }

    public function updateTicketCostingAmount()
    {
        try {
            if (!$this->isDoneCostingApprovals($this->ticket)) {
                if ($this->isOnlyAgent($this->ticket->agent_id)) {
                    $this->validate(['amount' => ['required', 'numeric']]);
                    $this->ticket->ticketCosting->update(['amount' => $this->amount]);
                    $this->editingFieldId = null;
                    $this->amount = null;
                } else {
                    $this->editingFieldId = null;
                    noty()->addWarning('Sorry, only agents have the authority to set the amount.');
                }
            } else {
                noty()->addWarning("Unable to update since ticket costing has already been approved.");
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteCostingAttachent(TicketCostingFile $ticketCostingFile)
    {
        try {
            if ($this->isOnlyAgent($this->ticket->agent_id) && !$this->isDoneSpecialProjectAmountApproval($this->ticket)) {
                if (Storage::exists($ticketCostingFile->file_attachment) && $ticketCostingFile->ticket_costing_id === $this->ticket->ticketCosting->id) {
                    $ticketCostingFile->delete();
                    Storage::delete($ticketCostingFile->file_attachment);
                } else {
                    noty()->addInfo('File not found.');
                }

                $this->dispatchBrowserEvent('close-costing-file-preview-modal');
                $this->emit('loadServiceDeptAdminTicketCosting');
            } else {
                $this->editingFieldId = null;
                noty()->addWarning('Deletion of attachment is restricted.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function saveAdditionalCostingFiles()
    {
        try {
            if ($this->isOnlyAgent($this->ticket->agent_id)) {
                if (!empty($this->additionalCostingFiles)) {
                    foreach ($this->additionalCostingFiles as $uploadedAdditionalCostingFile) {
                        $fileName = $uploadedAdditionalCostingFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/costing_attachments/" . $this->fileDirByUserType(),
                            $uploadedAdditionalCostingFile,
                            $fileName
                        );

                        $this->ticket->ticketCosting->fileAttachments()->create([
                            'file_attachment' => $fileAttachment,
                        ]);
                    }

                    $this->uploadFileCostingCount++;
                    $this->additionalCostingFiles = [];
                    $this->emit('loadServiceDeptAdminTicketCosting');

                    if ($this->ticket->ticketCosting->fileAttachments()->count() === 0) {
                        TicketSpecialProjectStatus::create([
                            'ticket_id' => $this->ticket->id,
                            'costing_and_planning_status' => SpecialProjectStatusEnum::DONE
                        ]);
                    }
                } else {
                    noty()->addError('File attachment for costing is required');
                }
            } else {
                $this->editingFieldId = null;
                noty()->addWarning('Sorry, only agents have the authority to add attachments.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function saveNewCostingFiles()
    {
        try {
            if ($this->isOnlyAgent($this->ticket->agent_id)) {
                if (!empty($this->newCostingFiles)) {
                    foreach ($this->newCostingFiles as $newCostingFile) {
                        $fileName = $newCostingFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/costing_attachments/" . $this->fileDirByUserType(),
                            $newCostingFile,
                            $fileName
                        );

                        $this->ticket->ticketCosting->fileAttachments()->create([
                            'file_attachment' => $fileAttachment,
                        ]);
                    }

                    $this->uploadFileCostingCount++;
                    $this->newCostingFiles = [];
                    $this->emit('loadServiceDeptAdminTicketCosting');
                    $this->dispatchBrowserEvent('close-new-ticket-costing-file-modal');

                    TicketSpecialProjectStatus::create([
                        'ticket_id' => $this->ticket->id,
                        'costing_and_planning_status' => SpecialProjectStatusEnum::DONE
                    ]);
                } else {
                    noty()->addError('File attachment for costing is required');
                }
            } else {
                $this->editingFieldId = null;
                noty()->addWarning('Sorry, only agents have the authority to add attachments.');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function isSpecialProjectCostingApprover1(int $approverId, Ticket $ticket)
    {
        return auth()->user()->id === $approverId
            && auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)
            && SpecialProjectAmountApproval::where('ticket_id', $ticket->id)
                ->whereJsonContains('service_department_admin_approver->approver_id', $approverId)
                ->exists();
    }

    public function approveCostingApproval1(Ticket $ticket)
    {
        try {
            $costingApprover1Id = User::role(Role::SERVICE_DEPARTMENT_ADMIN)->where('id', auth()->user()->id)->value('id');

            if ($this->isSpecialProjectCostingApprover1($costingApprover1Id, $ticket)) {
                if ($this->ticket->ticketCosting()->count() !== 0) {
                    SpecialProjectAmountApproval::where([['ticket_id', $ticket->id], ['service_department_admin_approver->is_approved', false]])
                        ->update([
                            'service_department_admin_approver->is_approved' => true,
                            'service_department_admin_approver->date_approved' => Carbon::now(),
                            'is_done' => !$this->isCostingAmountNeedCOOApproval($ticket) ? true : false
                        ]);

                    $this->emit('loadServiceDeptAdminTicketCosting');
                    noty()->addSuccess('Ticket costing is approved.');
                } else {
                    noty()->addError('Approval failed. Costing attachment is required');
                }
            } else {
                noty()->addWarning("Sorry, You have no permission to approve the costing");
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function isDoneTicketCostingAndPlanning(Ticket $ticket)
    {
        return TicketSpecialProjectStatus::whereNotNull('costing_and_planning_status')
            ->where([
                ['ticket_id', $ticket->id],
                ['costing_and_planning_status', SpecialProjectStatusEnum::DONE]
            ])->exists();
    }

    public function isPRApproved(Ticket $ticket)
    {
        return TicketCostingPRFile::where([
            ['ticket_costing_id', $ticket->ticketCosting->id],
            ['is_approved_level_1_approver', true],
            ['is_approved_level_2_approver', true],
        ])->exists();
    }

    public function setOrder()
    {
        TicketSpecialProjectStatus::where('ticket_id', $this->ticket->id)
            ->update(['purchasing_status' => SpecialProjectStatusEnum::ON_ORDERED]);

        $this->emit('loadServiceDeptAdminTicketCosting');
        $this->dispatchBrowserEvent('close-purchase-dropdown-menu');
    }

    public function setDeliver()
    {
        TicketSpecialProjectStatus::where('ticket_id', $this->ticket->id)
            ->update(['purchasing_status' => SpecialProjectStatusEnum::DELIVERED]);

        $this->emit('loadServiceDeptAdminTicketCosting');
        $this->dispatchBrowserEvent('close-purchase-dropdown-menu');
    }

    public function currentAgentInPurchasingTeam()
    {
        return PurchasingTeam::where('agent_id', auth()->user()->id)->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-costing');
    }
}
