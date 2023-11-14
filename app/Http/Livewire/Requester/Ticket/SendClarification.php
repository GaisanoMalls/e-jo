<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\StoreTicketClarificationRequest;
use App\Http\Traits\Utils;
use App\Mail\Requester\FromRequesterClarificationMail;
use App\Models\ActivityLog;
use App\Models\Clarification;
use App\Models\ClarificationFile;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Requester\TicketClarificationFromRequesterNotification;
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
        return (new StoreTicketClarificationRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTicketClarificationRequest())->messages();
    }

    private function actionOnSubmit()
    {
        sleep(1);
        $this->clarificationFiles = null;
        $this->upload++;
        $this->reset('description');
        $this->emit('loadTicketLogs');
        $this->emit('loadTicketDetails');
        $this->emit('loadBackButtonHeader');
        $this->emit('loadClarificationsCount');
        $this->emit('loadTicketClarifications');
        $this->emit('loadTicketStatusHeaderText');
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
                    'description' => $this->description
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

                // Get the latest staff
                $latestStaff = $clarification->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)
                    ->latest('created_at')
                    ->first();

                // Create a log description
                $logClarificationDescription = $this->ticket->clarifications()
                    ->where('user_id', '!=', auth()->user()->id)->count() === 0
                    ? 'sent a clarification'
                    : 'replied a clarification to ' . $latestStaff->user->profile->getFullName();

                // Get the department admin (approver) when there is no latest staff in the clarifications
                $initialServiceDepartmentAdmin = User::whereHas('role', fn($role) => $role->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN))
                    ->whereHas('branch', fn($branch) => $branch->where('branch_id', auth()->user()->branch_id))
                    ->whereHas('department', fn($department) => $department->where('department_id', auth()->user()->department_id))->first();

                ActivityLog::make($this->ticket->id, $logClarificationDescription);
                Notification::send($latestStaff->user ?? $initialServiceDepartmentAdmin, new TicketClarificationFromRequesterNotification($this->ticket));
                Mail::to($latestStaff->user ?? $initialServiceDepartmentAdmin)->send(new FromRequesterClarificationMail($this->ticket, $latestStaff->user ?? $initialServiceDepartmentAdmin, $this->description));
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.send-clarification');
    }
}
