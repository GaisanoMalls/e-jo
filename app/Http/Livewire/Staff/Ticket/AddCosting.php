<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\Agent\StoreTicketCostingRequest;
use App\Http\Traits\Utils;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\TicketCosting;
use App\Models\TicketCostingFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class AddCosting extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $uploadCostingCount = 0;
    public $amount;
    public $costingFiles = [];
    public $allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx', 'xlsx', 'xls', 'csv'];

    public function rules()
    {
        return (new StoreTicketCostingRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTicketCostingRequest())->messages();
    }

    private function actionOnSubmit()
    {
        $this->uploadCostingCount++;
        $this->reset('amount', 'costingFiles');
        $this->emit('loadServiceDeptAdminTicketCosting');
        $this->emit('loadCostingButtonHeader');
        $this->dispatchBrowserEvent('close-costing-modal');
    }

    public function saveCosting()
    {
        $this->validate();

        try {
            if ($this->isOnlyAgent($this->ticket->agent_id)) {
                $existingTicketCosting = TicketCosting::where('ticket_id', $this->ticket->id)->first();

                if ($existingTicketCosting) {
                    // Update the amount if costing exists.
                    $existingTicketCosting->update(['amount' => $this->amount]);
                } else {

                    // Get the costing approver id
                    $approver1Id = SpecialProjectAmountApproval::all()->pluck('service_department_admin_approver')
                        ->map(function ($item, $key) {
                            return $item['approver_id']; // Access the approver_id from each item
                        })->first();

                    $approver2Id = SpecialProjectAmountApproval::all()->pluck('fpm_coo_approver')
                        ->map(function ($item, $key) {
                            return $item['approver_id']; // Access the approver_id from each item
                        })->first();

                    if ($approver1Id && $approver2Id) {
                        // Create a costing when approver is found
                        $ticketCosting = TicketCosting::create([
                            'ticket_id' => $this->ticket->id,
                            'attached_by_id' => auth()->user()->id,
                            'amount' => $this->amount,
                        ]);

                        if (SpecialProjectAmountApproval::where('ticket_id', $this->ticket->id)->doesntExist()) {
                            SpecialProjectAmountApproval::create([
                                'ticket_id' => $this->ticket->id,
                                'service_department_admin_approver' => [
                                    'approver_id' => $approver1Id,
                                    'is_approved' => false,
                                    'date_approved' => null
                                ],
                                'fpm_coo_approver' => [
                                    'approver_id' => $approver2Id,
                                    'is_approved' => false,
                                    'date_approved' => null
                                ]
                            ]);
                        }
                    } else {
                        noty()->addError('No costing approver is found. Please contact the administrator');
                    }

                    if ($this->costingFiles) {
                        foreach ($this->costingFiles as $uploadedCostingFile) {
                            $fileName = $uploadedCostingFile->getClientOriginalName();
                            $fileAttachment = Storage::putFileAs(
                                "public/ticket/{$this->ticket->ticket_number}/costing_attachments/" . $this->fileDirByUserType(),
                                $uploadedCostingFile,
                                $fileName
                            );

                            $costingFile = new TicketCostingFile();
                            $costingFile->file_attachment = $fileAttachment;
                            $costingFile->ticket_costing_id = $ticketCosting->id;

                            $ticketCosting->fileAttachments()->save($costingFile);
                        }
                    }

                    $this->actionOnSubmit();
                }
            } else {
                $this->dispatchBrowserEvent('close-costing-modal');
                noty()->addError('Oops, something went wrong.');
            }

        } catch (\Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
        $this->actionOnSubmit();
    }

    public function updatedCostingFiles()
    {
        $this->validate([
            'costingFiles.*' => [
                'nullable',
                File::types($this->allowedExtensions)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.staff.ticket.add-costing');
    }
}
