<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\ReplyTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Requester\RequesterReplyMail;
use App\Models\ActivityLog;
use App\Models\Reply;
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

class SendTicketReply extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public int $upload = 0;
    public ?string $description = null;
    public $replyFiles = [];

    public function rules()
    {
        return (new ReplyTicketRequest())->rules();
    }

    public function messages()
    {
        return (new ReplyTicketRequest())->messages();
    }

    /**
     * Emits Livewire events to refresh ticket-related UI components.
     * 
     * Dispatches events to synchronize all ticket interface elements after state changes.
     * Ensures consistent display of:
     * - Activity logs
     * - Notification indicators
     * - Core ticket information
     * - Discussion counters
     * - Conversation threads
     * - Status displays
     * 
     * Events are emitted in sequence to maintain UI consistency.
     */
    private function triggerEvents()
    {
        // Array of Livewire events to trigger component refreshes
        $events = [
            'loadTicketLogs',            // Refresh the activity log display
            'loadNewReplyIcon',          // Update new reply notification badge
            'loadTicketDetails',         // Reload main ticket information
            'loadDiscussionsCount',      // Update discussion counter
            'loadTicketDiscussions',     // Refresh discussion thread
            'loadTicketStatusHeaderText' // Update status text in header
        ];

        // Emit each event to notify listening components
        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    /**
     * Performs post-submission cleanup and UI reset operations.
     * 
     * Handles the following actions after a successful form submission:
     * 1. Clears stored file attachments
     * 2. Forces file input refresh
     * 3. Triggers UI component updates
     * 4. Resets the description field
     * 5. Manages modal window state
     * 
     * Ensures a clean state for subsequent form submissions.
     */
    private function actionOnSubmit()
    {
        // Clear stored file attachments from the reply
        $this->replyFiles = [];

        // Increment counter to force file input refresh (bypasses browser cache)
        $this->upload++;

        // Trigger all necessary Livewire events to refresh UI components
        $this->triggerEvents();

        // Reset only the description field while preserving other form state
        $this->reset('description');

        // Close the current modal dialog
        $this->dispatchBrowserEvent('close-modal');

        // Reinitialize modal state for next use
        $this->dispatchBrowserEvent('reload-modal');
    }

    /**
     * Handles the process of sending a reply to a ticket.
     *
     * This function performs the following steps:
     * 1. Validates the input data.
     * 2. Executes a database transaction to:
     *    - Update the ticket's status to "ON_PROCESS".
     *    - Create a new reply associated with the ticket.
     *    - Attach any uploaded files to the reply.
     *    - Notify service department admins via email and in-app notifications.
     *    - Log the reply activity.
     * 3. Performs post-submission actions such as UI updates and modal closure.
     *
     * @return void
     * @throws Exception If an error occurs during the database transaction or notification process.
     */
    public function sendTicketReply()
    {
        // Validate the input data before proceeding.
        $this->validate();

        try {
            // Begin a database transaction to ensure atomicity.
            DB::transaction(function () {
                // Update the ticket's status to "ON_PROCESS".
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                // Create a new reply associated with the ticket.
                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description,
                ]);

                // Handle file attachments if any are provided.
                if ($this->replyFiles) {
                    collect($this->replyFiles)->each(function ($uploadedReplyFile) use ($reply) {
                        // Get the original file name.
                        $fileName = $uploadedReplyFile->getClientOriginalName();

                        // Store the file in the appropriate directory.
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(),
                            $uploadedReplyFile,
                            $fileName
                        );

                        // Associate the file attachment with the reply.
                        $reply->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    });
                }

                // Retrieve the latest reply from the user for the ticket.
                $latestReply = Reply::where('ticket_id', $this->ticket->id)
                    ->withWhereHas('user', fn($user) => $user->role(Role::USER))
                    ->latest('created_at')->first();

                // Retrieve the latest reply from staff (excluding the current user).
                $latestStaff = $reply->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)
                    ->latest('created_at')->first();

                // Fetch all service department admins.
                $serviceDepartmentAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->with(['branches', 'buDepartments', 'serviceDepartments'])
                    ->get();

                // Notify service department admins if they are associated with the ticket.
                $serviceDepartmentAdmins->each(function ($serviceDepartmentAdmin) use ($latestStaff) {
                    if (
                        $this->ticket->whereIn('service_department_id', $serviceDepartmentAdmin->serviceDepartments->pluck('id'))
                            ->whereIn('branch_id', $serviceDepartmentAdmin->branches->pluck('id'))
                            ->orWhereHas('user', function ($user) use ($serviceDepartmentAdmin) {
                                $user->whereHas('branches', function ($branch) use ($serviceDepartmentAdmin) {
                                    $branch->whereIn('branches.id', $serviceDepartmentAdmin->branches->pluck('id'));
                                })
                                    ->whereHas('buDepartments', function ($department) use ($serviceDepartmentAdmin) {
                                        $department->whereIn('departments.id', $serviceDepartmentAdmin->buDepartments->pluck('id'));
                                    });
                            })
                            ->exists()
                    ) {
                        // Send an in-app notification to the service department admin.
                        Notification::send(
                            $serviceDepartmentAdmin,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (Replied)",
                                message: "Ticket reply from {$this->ticket->user->profile->getFullName}",
                            )
                        );
                        // Send an email notification to the service department admin.
                        Mail::to($serviceDepartmentAdmin)
                            ->send(new RequesterReplyMail(
                                $this->ticket,
                                $serviceDepartmentAdmin,
                                $this->description
                            ));
                    }
                });
                // Log the reply activity in the activity log.
                ActivityLog::make(ticket_id: $this->ticket->id, description: "replied to {$latestReply->user->profile->getFullName}");
            });

            // Perform post-submission actions such as UI updates and modal closure.
            $this->actionOnSubmit();

        } catch (Exception $e) {
            // Log the error if an exception occurs.
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.send-ticket-reply');
    }
}
