<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\TicketCosting as Costing;
use App\Models\TicketCostingFile;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
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
    public $allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx', 'xlsx', 'xls', 'csv'];
    protected $listeners = ['loadServiceDeptAdminTicketCosting' => '$refresh'];

    public function updatedNewCostingFiles()
    {
        $this->validate([
            'newCostingFiles.*' => [
                'nullable',
                File::types($this->allowedExtensions)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function updatedAdditionalCostingFiles()
    {
        $this->validate([
            'newCostingFiles.*' => [
                'nullable',
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

    public function isCostingGreaterOrEqual()
    {
        return $this->ticket->ticketCosting?->amount >= $this->ticket->helpTopic->specialProject?->amount;
    }

    public function updateTicketCostingAmount()
    {
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
    }

    public function deleteCostingAttachent(TicketCostingFile $ticketCostingFile)
    {
        if ($this->isOnlyAgent($this->ticket->agent_id)) {
            if (Storage::exists($ticketCostingFile->file_attachment)) {
                $ticketCostingFile->delete();
                Storage::delete($ticketCostingFile->file_attachment);
            } else {
                noty()->addInfo('File not found.');
            }

            $this->dispatchBrowserEvent('close-costing-file-preview-modal');
            $this->emit('loadServiceDeptAdminTicketCosting');
        } else {
            $this->editingFieldId = null;
            noty()->addWarning('Sorry, only agents have the authority to delete the attachments.');
        }
    }

    public function saveAdditionalCostingFiles()
    {
        if ($this->isOnlyAgent($this->ticket->agent_id)) {
            if ($this->additionalCostingFiles) {
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
            }
        } else {
            $this->editingFieldId = null;
            noty()->addWarning('Sorry, only agents have the authority to add attachments.');
        }
    }

    public function saveNewCostingFiles()
    {
        if ($this->isOnlyAgent($this->ticket->agent_id)) {
            if ($this->newCostingFiles) {
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
            }
        } else {
            $this->editingFieldId = null;
            noty()->addWarning('Sorry, only agents have the authority to add attachments.');
        }
    }

    public function isSpecialProjectCostingApprover1(int $approverId)
    {
        return auth()->user()->id === $approverId
            && auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN)
            && SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)->whereJsonContains('service_department_admin_approver->approver_id', $approverId)->exists();
    }

    public function approveCostingApproval1()
    {
        try {
            $costingApprover1Id = User::role(Role::SERVICE_DEPARTMENT_ADMIN)->where('id', auth()->user()->id)->value('id');

            if ($this->isSpecialProjectCostingApprover1($costingApprover1Id)) {
                SpecialProjectAmountApproval::where([['ticket_id', $this->ticket->id], ['service_department_admin_approver->is_approved', false]])
                    ->update([
                        'service_department_admin_approver->is_approved' => true,
                        'service_department_admin_approver->date_approved' => Carbon::now(),
                        'is_done' => !$this->isCostingAmountNeedCOOApproval($this->ticket) ? true : false
                    ]);

                $this->emit('loadServiceDeptAdminTicketCosting');
                noty()->addSuccess('Ticket costing is approved.');
            } else {
                noty()->addWarning("Sorry, You have no permission to approve the costing");
            }
        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function isCostingApproval1Approved()
    {
        return SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)
            ->whereJsonContains('service_department_admin_approver->is_approved', true)->exists();
    }

    public function render()
    {
        return view('livewire.staff.ticket.ticket-costing');
    }
}
