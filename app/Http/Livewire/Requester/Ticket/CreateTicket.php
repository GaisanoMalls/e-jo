<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Mail\Requester\TicketCreatedMail;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\Role;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketFile;
use App\Models\User;
use App\Notifications\Requester\TicketCreatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTicket extends Component
{
    use Utils, BasicModelQueries, WithFileUploads;

    public $upload = 0;
    public $fileAttachments = [], $helpTopics = [];
    public $subject, $description;
    public $branch, $team, $sla, $priorityLevel, $serviceDepartment, $helpTopic;

    protected $listeners = ['clearTicketErrorMessages' => 'clearErrorMessage'];

    public function rules()
    {
        return (new StoreTicketRequest())->rules();
    }

    public function messages()
    {
        return (new StoreTicketRequest())->messages();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function clearErrorMessage()
    {
        $this->resetValidation();
    }

    public function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->fileAttachments = null;
        $this->upload++;
        $this->emit('loadDashboard');
        $this->emit('loadTicketTab');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
        $this->dispatchBrowserEvent('clear-branch-dropdown-select');
    }

    public function sendTicket()
    {
        $this->validate();
        try {
            DB::transaction(function () {
                $ticket = Ticket::create([
                    'user_id' => Auth::user()->id,
                    'branch_id' => (int) $this->branch ?: Auth::user()->branch->id,
                    'service_department_id' => (int) $this->serviceDepartment,
                    'team_id' => $this->team != 'undefined' ? $this->team : null,
                    'help_topic_id' => (int) $this->helpTopic,
                    'status_id' => Status::OPEN,
                    'priority_level_id' => (int) $this->priorityLevel,
                    'service_level_agreement' => $this->sla,
                    'ticket_number' => $this->generatedTicketNumber(),
                    'subject' => $this->subject,
                    'description' => $this->description,
                    'approval_status' => ApprovalStatus::FOR_APPROVAL,
                    // 'service_department_admin_approver' => [
                    //     'service_department_admin_id' => UserServiceDepartment::where('service_department_id', $this->serviceDepartment)->pluck('user_id')->first(),
                    //     'is_approved' => false
                    // ]
                ]);

                if ($this->fileAttachments) {
                    foreach ($this->fileAttachments as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/creation_attachments", $uploadedFile, $fileName);

                        $ticketFile = new TicketFile();
                        $ticketFile->file_attachment = $fileAttachment;
                        $ticketFile->ticket_id = $ticket->id;

                        $ticket->fileAttachments()->save($ticketFile);
                    }
                }

                // ! TEMPORARY
                // Notify approvers through email and app based notification.
                // $levelApprovers = LevelApprover::where('help_topic_id', $ticket->helpTopic->id)->get();
                // $approvers = User::approvers();

                // foreach ($ticket->helpTopic->levels as $level) {
                //     foreach ($levelApprovers as $levelApprover) {
                //         foreach ($approvers as $approver) {
                //             if ($approver->id === $levelApprover->user_id) {
                //                 if ($levelApprover->level_id === $level->id) {
                //                     if ($approver->buDepartments->pluck('id')->first() === $ticket->user->department_id) {
                //                         Notification::send($approver, new TicketCreatedNotification($ticket));
                //                         // Mail::to($approver)->send(new TicketCreatedMail($ticket));
                //                     }
                //                 }
                //             }
                //         }
                //     }
                // }

                // Email the first approver (Service Department Admin)
                $serviceDepartmentAdmin = User::whereHas('role', fn($query) => $query->where('role_id', Role::SERVICE_DEPARTMENT_ADMIN))
                    ->whereHas('branch', fn($query) => $query->where('branch_id', $ticket->branch_id))
                    ->withWhereHas('serviceDepartments', fn($query) => $query->where('service_departments.id', $ticket->service_department_id))->first();

                if ($serviceDepartmentAdmin) {
                    Notification::send($serviceDepartmentAdmin, new TicketCreatedNotification($ticket));
                    Mail::to($serviceDepartmentAdmin)->send(new TicketCreatedMail($ticket, $serviceDepartmentAdmin));
                }

                ActivityLog::make($ticket->id, 'created a ticket');
            });

            $this->actionOnSubmit();
            flash()->addSuccess('Ticket successfully created.');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong.');
        }
    }

    public function updatedServiceDepartment()
    {
        $this->helpTopics = HelpTopic::with(['team', 'sla'])->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', (int) $this->serviceDepartment))->get();
        $this->dispatchBrowserEvent('get-help-topics-from-service-department', ['helpTopics' => $this->helpTopics]);
    }

    public function updatedHelpTopic()
    {
        $this->team = Team::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', (int) $this->helpTopic))->pluck('id')->first();
        $this->sla = ServiceLevelAgreement::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', (int) $this->helpTopic))->pluck('id')->first();
    }

    public function render()
    {
        return view('livewire.requester.ticket.create-ticket', [
            'priorityLevels' => $this->queryPriorityLevels(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'branches' => Branch::where('id', '!=', auth()->user()->branch_id)->get()
        ]);
    }
}