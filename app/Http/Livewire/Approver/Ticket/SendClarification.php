<?php

namespace App\Http\Livewire\Approver\Ticket;

use App\Http\Requests\ServiceDeptAdmin\StoreClarificationRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Staff\StaffClarificationMail;
use App\Models\ActivityLog;
use App\Models\Clarification;
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

    /**
     * Performs cleanup and UI refresh actions after form submission.
     * 
     * This method handles post-submission tasks by:
     * 1. Resetting form-related properties:
     *    - Clearing clarification files
     *    - Incrementing upload counter
     *    - Resetting description field
     * 2. Emitting events to refresh various UI components:
     *    - Ticket logs
     *    - Ticket details
     *    - Clarifications list
     *    - Latest clarification
     *    - Status header text
     * 3. Managing modal windows:
     *    - Closing current modal
     *    - Triggering modal reload
     * 
     * @return void
     * @fires loadTicketLogs
     * @fires loadTicketDetails
     * @fires loadClarifications
     * @fires loadLatestClarification
     * @fires loadTicketStatusHeaderText
     * @dispatches close-modal Browser event
     * @dispatches reload-modal Browser event
     */
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

    /**
     * Processes and sends a ticket clarification with optional attachments.
     *
     * This method handles the complete clarification workflow:
     * 1. Validates input data
     * 2. Updates ticket status to ON_PROCESS
     * 3. Creates a new clarification record
     * 4. Processes file attachments if present
     * 5. Notifies relevant parties (Service Department Admins and requester)
     * 6. Logs the activity
     * 7. Performs post-submission cleanup
     *
     * The operation runs in a database transaction to ensure data consistency.
     * Includes proper error handling that logs exceptions to AppErrorLog.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException If validation fails
     * @throws \Exception If any other error occurs during processing (handled internally)
     *
     * @uses \App\Models\Clarification For creating clarification records
     * @uses \App\Models\Status For ON_PROCESS status
     * @uses \App\Notifications\AppNotification For sending notifications
     * @uses \App\Mail\StaffClarificationMail For sending email notifications
     * @uses \App\Models\ActivityLog For activity tracking
     * @uses \App\Models\AppErrorLog For error logging
     *
     * @fires actionOnSubmit After successful processing
     * @dispatches \Illuminate\Notifications\Notification To Service Department Admins
     * @dispatches \Illuminate\Mail\Mailable To ticket requester
     */
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
                        $serviceDepartment->whereIn('departments.id', $this->ticket->user->buDepartments->pluck('id'));
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

                Notification::send(
                        $requester->user ?? $this->ticket->user,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Clarification)",
                            message: auth()->user()->profile->getFullName . " sent a clarification",
                            forClarification: true
                        )
                    );

                Mail::to($this->ticket->user)->send(new StaffClarificationMail($this->ticket, $this->ticket->user, $this->description));
                ActivityLog::make(ticket_id: $this->ticket->id, description: $logDescription);
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
