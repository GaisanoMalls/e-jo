<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\ServiceDeptAdmin\StoreClarificationRequest;
use App\Http\Traits\Utils;
use App\Mail\Staff\FromApproverClarificationMail;
use App\Models\ActivityLog;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Status;
use App\Models\Ticket;
use App\Notifications\ServiceDepartmentAdmin\TicketClarificationFromServiceDeptAdminNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendClarification extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public $upload = 0;
    public $description;
    public $clarificationFiles = [];

    public function rules()
    {
        return (new StoreClarificationRequest())->rules();
    }

    public function messages()
    {
        return (new StoreClarificationRequest())->messages();
    }

    private function actionOnSubmit()
    {
        $this->replyFiles = null;
        $this->upload++;
        $this->reset('description');
        $this->emit('loadTicketLogs');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadClarificationCount');
        $this->emit('loadTicketClarifications');
        $this->emit('loadTicketStatusTextHeader');
        $this->emit('loadSidebarCollapseTicketStatus');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
    }

    public function sendClarification()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                $clarification = Clarification::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description,
                ]);

                if ($this->clarificationFiles) {
                    foreach ($this->clarificationFiles as $uploadedClarificationFile) {
                        $fileName = $uploadedClarificationFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/clarification_attachments/" . $this->fileDirByUserType(),
                            $uploadedClarificationFile,
                            $fileName
                        );

                        $clarificationFile = new ClarificationFile();
                        $clarificationFile->file_attachment = $fileAttachment;
                        $clarificationFile->clarification_id = $clarification->id;
                        $clarification->fileAttachments()->save($clarificationFile);
                    }
                }

                // * GET THE REQUESTER
                $requester = $clarification->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)->latest('created_at')->first();

                // * CONSTRUCT A LOG DESCRIPTION
                $logDescription = ($this->ticket->clarifications()->where('user_id', '!=', auth()->user()->id)->count() === 0)
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $requester->user->profile->getFullName();

                ActivityLog::make($this->ticket->id, $logDescription);
                Notification::send($this->ticket->user, new TicketClarificationFromServiceDeptAdminNotification($this->ticket));
                // Mail::to($this->ticket->user)->send(new FromApproverClarificationMail($this->ticket, $this->ticket->user, $this->description));

            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.send-clarification');
    }
}
