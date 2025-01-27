<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Requests\ServiceDeptAdmin\StoreClarificationRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Staff\FromApproverClarificationMail;
use App\Models\ActivityLog;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
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

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadBackButtonHeader',
            'loadClarificationCount',
            'loadTicketClarifications',
            'loadTicketStatusTextHeader',
            'loadSidebarCollapseTicketStatus',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->replyFiles = null;
        $this->upload++;
        $this->triggerEvents();
        $this->reset('description');
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
                    collect($this->clarificationFiles)->each(function ($uploadedClarificationFile) use ($clarification) {
                        $fileName = $uploadedClarificationFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/clarification_attachments/" . $this->fileDirByUserType(),
                            $uploadedClarificationFile,
                            $fileName
                        );
                        $clarification->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    });
                }

                // GET THE REQUESTER
                $requester = $clarification->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)->latest('created_at')->first();

                // CONSTRUCT A LOG DESCRIPTION
                $logDescription = ($this->ticket->clarifications()->where('user_id', '!=', auth()->user()->id)->count() === 0)
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $requester->user->profile->getFullName;

                // Retrieve the service department administrator responsible for approving the ticket. For notification use only
                $serviceDepartmentAdmin = User::with('profile')->where('id', auth()->user()->id)->role(Role::SERVICE_DEPARTMENT_ADMIN)->first();

                Notification::send(
                    $this->ticket->user,
                    new AppNotification(
                        ticket: $this->ticket,
                        title: "Ticket #{$this->ticket->ticket_number} (Clarification)",
                        message: "Ticket clarification from {$serviceDepartmentAdmin->profile->getFullName} ",
                        forClarification: true
                    )
                );
                Mail::to($this->ticket->user)->send(new FromApproverClarificationMail($this->ticket, $this->ticket->user, $this->description));
                ActivityLog::make(ticket_id: $this->ticket->id, description: $logDescription);
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.ticket.send-clarification');
    }
}
