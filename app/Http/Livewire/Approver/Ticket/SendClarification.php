<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Requests\ServiceDeptAdmin\StoreClarificationRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
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
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendClarification extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public int $upload = 0;
    public ?string $description = null;
    public $clarificationFiles = [];

    public function rules()
    {
        return (new StoreClarificationRequest())->rules();
    }

    public function messages()
    {
        return (new StoreClarificationRequest())->messages();
    }

    /** Perform livewire events upon form submission. */
    private function actionOnSubmit()
    {
        $this->clarificationFiles = [];
        $this->upload++;
        $this->reset('description');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadClarifications');
        $this->emit('loadLatestClarification');
        $this->emit('loadTicketStatusHeaderText');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
    }

    public function sendClarification()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                //  Update ticket status
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                // Create and save the clarification.
                $clarification = Clarification::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description,
                ]);

                // Check if clarification has file attachment/s.
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

                // Get the current requester (Sender of the ticket clarification).
                $requester = $clarification->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)
                    ->latest('created_at')->first();

                // Make a log message.
                $logDescription = $this->ticket->clarifications()->where('user_id', '!=', auth()->user()->id)->count() == 0
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $requester->user->profile->getFullName;

                $requesterServiceDepartmentAdmins = User::with('profile')
                    ->whereHas('buDepartments', function ($serviceDepartment) {
                        $serviceDepartment->whereIn('departments.id', $this->ticket->user->buDepartments->pluck('id')->toArray());
                    })
                    ->role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->get();

                $requesterServiceDepartmentAdmins->each(function ($serviceDeptAdmin) {
                    Notification::send(
                        $serviceDeptAdmin,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Clarification)",
                            message: auth()->user()->profile->getFullName . " sent a clarification",
                            forClarification: true
                        )
                    );
                });

                ActivityLog::make(ticket_id: $this->ticket->id, description: $logDescription);
                // Mail::to($ticket->user)->send(new FromApproverClarificationMail($ticket, $request->description));
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.approver.ticket.send-clarification');
    }
}
