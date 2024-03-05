<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\StoreCostingPRFileRequest;
use App\Http\Traits\Utils;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Ticket;
use App\Models\TicketCostingPRFile;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketCosting extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $uploadPRFileCostingCount = 0;
    public $costingPRFiles = [];
    public $prFileAllowedExtension = ['pdf'];

    protected $listeners = [
        'loadRequesterTicketCosting' => '$refresh',
        'loadRequesterTicketCostingPRFile' => '$refresh',
    ];

    public function rules()
    {
        return(new StoreCostingPRFileRequest())->rules();
    }

    public function messages()
    {
        return(new StoreCostingPRFileRequest())->messages();
    }

    private function actionOnSubmit()
    {
        $this->uploadPRFileCostingCount++;
        $this->costingPRFiles = [];
        $this->resetValidation();
        $this->emit('loadRequesterTicketCosting');
        $this->emit('loadRequesterTicketCostingPRFile');
    }


    public function saveCostingPRFile()
    {
        try {
            if ($this->isCurrentRequesterIsOwnerOfCosting() && !$this->isDonePRFileApproval($this->ticket)) {
                if ($this->costingPRFiles) {
                    foreach ($this->costingPRFiles as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$this->ticket->ticket_number}/costing-pr-file", $uploadedFile, $fileName);

                        $costingPRFile = new TicketCostingPRFile();
                        $costingPRFile->file_attachment = $fileAttachment;
                        $costingPRFile->ticket_costing_id = $this->ticket->ticketCosting->id;

                        $this->ticket->ticketCosting->prFileAttachments()->save($costingPRFile);

                        $this->actionOnSubmit();
                    }
                } else {
                    session()->flash('error', 'PR file is required');
                }
            } else {
                noty()->addError('Adding of attachments is restricted.');
            }

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function deleteCostingPRFile(TicketCostingPRFile $prFile, Ticket $ticket)
    {
        try {
            if ($this->isCurrentRequesterIsOwnerOfCosting() && !$this->isDonePRFileApproval($ticket)) {
                if (Storage::exists($prFile->file_attachment) && $prFile->ticket_costing_id === $ticket->ticketCosting->id) {
                    $prFile->delete();
                    Storage::delete($prFile->file_attachment);
                    $this->emit('loadRequesterTicketCostingPRFile');
                    $this->emit('loadRequesterTicketCosting');
                } else {
                    noty()->addInfo('File not found.');
                }
            } else {
                noty()->addError('Deletion of attachment is restricted');
            }
        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function isCurrentRequesterIsOwnerOfCosting()
    {
        return auth()->user()->id === $this->ticket->user->id;
    }

    public function isDonePRFileApproval(Ticket $ticket)
    {
        return TicketCostingPRFile::where([
            ['ticket_costing_id', $ticket->ticketCosting->id],
            ['is_approved_level_1_approver', true],
        ])->orWhere('is_approved_level_2_approver', true)
            ->exists();
    }

    public function updatedCostingPRFiles()
    {
        $this->validate([
            'costingPRFiles.*' => [
                'nullable',
                File::types($this->prFileAllowedExtension)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.requester.ticket.ticket-costing');
    }
}
