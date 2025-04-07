<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\StoreTicketClarificationRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Requester\RequesterClarificationMail;
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
    public string|array $clarificationFiles = [];

    public function rules()
    {
        return (new StoreTicketClarificationRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTicketClarificationRequest())->messages();
    }

    /**
     * Triggers multiple ticket-related events to refresh UI components.
     * 
     * Emits events to update various ticket interface elements including:
     * - Logs and activity history
     * - Main ticket details
     * - Navigation elements
     * - Clarification counters and indicators
     * - Status displays
     *
     * This ensures all related UI components stay synchronized after state changes.
     *
     * @return void
     * @fires loadTicketLogs Refreshes ticket activity log
     * @fires loadTicketDetails Reloads main ticket information
     * @fires loadBackButtonHeader Updates navigation header
     * @fires loadClarificationsCount Updates clarification counter
     * @fires loadNewClarificationIcon Updates new clarification indicator
     * @fires loadTicketClarifications Reloads clarification list
     * @fires loadTicketStatusHeaderText Updates status display
     */
    private function triggerEvents()
    {
        // List of all events to emit for full UI refresh
        $events = [
            'loadTicketLogs',              // Activity log updates
            'loadTicketDetails',           // Main ticket data
            'loadBackButtonHeader',        // Navigation header
            'loadClarificationsCount',     // Clarification counter
            'loadNewClarificationIcon',    // New message indicator
            'loadTicketClarifications',    // Full clarification list
            'loadTicketStatusHeaderText',  // Status display
        ];

        // Emit each event in sequence
        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    /**
     * Executes post-submission cleanup and UI reset operations.
     * 
     * Performs the following actions in sequence:
     * 1. Clears stored file attachments
     * 2. Forces UI refresh for file inputs
     * 3. Triggers related component updates
     * 4. Resets form description field
     * 5. Manages modal window state
     * 
     * This ensures a clean state for subsequent form submissions.
     */
    private function actionOnSubmit()
    {
        // Clear stored file attachments from previous submission
        $this->clarificationFiles = [];

        // Increment counter to force file input refresh (bypass browser cache)
        $this->upload++;

        // Trigger all necessary Livewire events to refresh related components
        $this->triggerEvents();

        // Reset just the description field
        $this->reset('description');

        // Close the current modal dialog
        $this->dispatchBrowserEvent('close-modal');

        // Reinitialize modal for next use (resets internal modal state)
        $this->dispatchBrowserEvent('reload-modal');
    }

    /**
     * Processes and sends a ticket clarification with attachments and notifications.
     * 
     * This method handles the complete clarification workflow:
     * 1. Validates input data
     * 2. Updates ticket status to ON_PROCESS
     * 3. Creates a clarification record
     * 4. Processes and stores file attachments
     * 5. Determines notification recipients (staff or department admins)
     * 6. Sends in-app notifications and emails
     * 7. Logs the activity
     * 8. Performs post-submission cleanup
     * 
     * All operations are performed within a database transaction for data integrity.
     * Errors are caught and logged to the application error system.
     */
    public function sendClarification()
    {
        $this->validate(); // Validate form inputs before processing

        try {
            DB::transaction(function () {
                // Update ticket status to indicate it's being processed
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                // Create the clarification record
                $clarification = Clarification::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description,
                ]);

                // Process file attachments if any were uploaded
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

                // Find the most recent staff member who interacted with this ticket
                $latestStaff = $clarification->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)
                    ->latest('created_at')->first();

                // Create appropriate activity log description based on context
                $logClarificationDescription = $this->ticket->clarifications()
                    ->where('user_id', '!=', auth()->user()->id)->count() === 0
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $latestStaff->user->profile->getFullName;

                // Get department admins as fallback notification recipients
                $initialServiceDepartmentAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->whereHas('branches', fn($branch) =>
                        $branch->where('branches.id', auth()->user()->branches->pluck('id')->first()))
                    ->whereHas('buDepartments', fn($query) =>
                        $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first()))
                    ->get();

                // Send notifications to appropriate recipients
                $initialServiceDepartmentAdmins->each(function ($initialServiceDepartmentAdmin) use ($latestStaff) {
                    Notification::send(
                        $latestStaff->user ?? $initialServiceDepartmentAdmin,
                        new AppNotification(
                            ticket: $this->ticket,
                            title: "Ticket #{$this->ticket->ticket_number} (Clarification)",
                            message: "Ticket clarification from {$this->ticket->user->profile->getFullName}",
                            forClarification: true
                        )
                    );
                    Mail::to($latestStaff->user ?? $initialServiceDepartmentAdmin)
                        ->send(new RequesterClarificationMail(
                            $this->ticket,
                            $latestStaff->user ?? $initialServiceDepartmentAdmin,
                            $this->description
                        ));
                });

                // Record the activity in logs
                ActivityLog::make(ticket_id: $this->ticket->id, description: $logClarificationDescription);

                // Perform post-submission cleanup
                $this->actionOnSubmit();
            });
        } catch (Exception $e) {
            // Log any errors that occur during the process
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.send-clarification');
    }
}
