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

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadTicketDetails',
            'loadBackButtonHeader',
            'loadClarificationsCount',
            'loadNewClarificationIcon',
            'loadTicketClarifications',
            'loadTicketStatusHeaderText',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->clarificationFiles = [];
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

                // Get the latest staff
                $latestStaff = $clarification->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)
                    ->latest('created_at')->first();

                // Create a log description
                $logClarificationDescription = $this->ticket->clarifications()
                    ->where('user_id', '!=', auth()->user()->id)->count() === 0
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $latestStaff->user->profile->getFullName;

                // Get the department admin (approver) when there is no latest staff in the clarifications
                $initialServiceDepartmentAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->whereHas('branches', fn($branch) =>
                        $branch->where('branches.id', auth()->user()->branches->pluck('id')->first()))
                    ->whereHas('buDepartments', fn($query) =>
                        $query->where('departments.id', auth()->user()->buDepartments->pluck('id')->first()))
                    ->get();

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

                ActivityLog::make(ticket_id: $this->ticket->id, description: $logClarificationDescription);
                $this->actionOnSubmit();
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.send-clarification');
    }
}
