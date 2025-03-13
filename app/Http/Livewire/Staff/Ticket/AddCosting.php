<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Enums\SpecialProjectStatusEnum;
use App\Http\Requests\Agent\StoreTicketCostingRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\TicketCosting;
use App\Models\TicketCostingFile;
use App\Models\TicketSpecialProjectStatus;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class AddCosting extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $uploadCostingCount = 0;
    public $amount;
    public $costingFiles = [];
    public $allowedExtensions = ['pdf'];

    public function rules()
    {
        return (new StoreTicketCostingRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTicketCostingRequest())->messages();
    }

    private function triggerEvents()
    {
        $events = [
            'loadServiceDeptAdminTicketCosting',
            'loadCostingButtonHeader',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->uploadCostingCount++;
        $this->triggerEvents();
        $this->reset('amount', 'costingFiles');
        $this->dispatchBrowserEvent('close-costing-modal');
    }

    public function saveCosting()
    {
        $this->validate();

        try {
            if ($this->isOnlyAgent($this->ticket->agent_id)) {
                if (!empty($this->costingFiles)) {
                    TicketSpecialProjectStatus::create([
                        'ticket_id' => $this->ticket->id,
                        'costing_and_planning_status' => SpecialProjectStatusEnum::DONE
                    ]);

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
                                'amount' => $this->amount,
                            ]);

                            if (SpecialProjectAmountApproval::whereNotNull('ticket_id')->exists()) {
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
                            } else {
                                SpecialProjectAmountApproval::whereNull('ticket_id')
                                    ->whereNotNull([
                                        'service_department_admin_approver',
                                        'fpm_coo_approver'
                                    ])->update(['ticket_id' => $this->ticket->id]);
                            }
                        } else {
                            noty()->addWarning('No cosmessage: ting approver is found. Please contact the administrator.');
                        }

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

                        $this->actionOnSubmit();
                    }
                } else {
                    $this->addError('costingFiles', 'File attachment for costing is required');
                }
            } else {
                $this->dispatchBrowserEvent('close-costing-modal');
                noty()->addError('Oops, something went wrong.');
            }

        } catch (\Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
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
