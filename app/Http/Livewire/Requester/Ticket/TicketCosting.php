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
        return (new StoreCostingPRFileRequest())->rules();
    }

    public function messages()
    {
        return (new StoreCostingPRFileRequest())->messages();
    }

    public function actionOnSubmit()
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
            if ($this->isCurrentRequesterIsOwnerOfCosting()) {
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
                noty()->addError('Unverified user');
            }

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function deleteCostingPRFile(TicketCostingPRFile $prFile)
    {
        try {
            if ($this->isCurrentRequesterIsOwnerOfCosting()) {
                if (Storage::exists($prFile->file_attachment)) {
                    $prFile->delete();
                    Storage::delete($prFile->file_attachment);
                    $this->emit('loadRequesterTicketCostingPRFile');
                    $this->emit('loadRequesterTicketCosting');
                } else {
                    noty()->addInfo('File not found.');
                }
            } else {
                noty()->addError('Unverified user');
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

    public function updatedCostingPRFiles()
    {
        $this->validate([
            'costingPRFiles.*' => [
                'nullable',
                File::types($this->prFileAllowedExtension)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function isDoneSpecialProjectAmountApproval(Ticket $ticket)
    {
        return SpecialProjectAmountApproval::where([
            ['ticket_id', $ticket->id],
            ['is_done', true],
        ])->exists();
    }


    public function render()
    {
        return view('livewire.requester.ticket.ticket-costing');
    }
}
