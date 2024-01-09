<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Mail\Requester\TicketCreatedMail;
use App\Models\ActivityLog;
use App\Models\ApprovalStatus;
use App\Models\ApproverLevel;
use App\Models\Branch;
use App\Models\HelpTopic;
use App\Models\Level;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketFile;
use App\Models\TicketTeam;
use App\Notifications\Requester\TicketCreatedNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
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
    public $fileAttachments = [];
    public $helpTopics = [];
    public $subject;
    public $description;
    public $branch;
    public $team;
    public $sla;
    public $priorityLevel;
    public $serviceDepartment;
    public $helpTopic;

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

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->fileAttachments = null;
        $this->upload++;
        $this->emit('loadDashboard');
        $this->emit('loadTicketTab');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('clear-branch-dropdown-select');
    }

    public function sendTicket()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $ticket = Ticket::create([
                    'user_id' => Auth::user()->id,
                    'branch_id' => $this->branch ?: Auth::user()->branches->pluck('id')->first(),
                    'service_department_id' => $this->serviceDepartment,
                    'help_topic_id' => $this->helpTopic,
                    'status_id' => Status::OPEN,
                    'priority_level_id' => $this->priorityLevel,
                    'service_level_agreement' => $this->sla,
                    'ticket_number' => $this->generatedTicketNumber(),
                    'subject' => $this->subject,
                    'description' => $this->description,
                    'approval_status' => ApprovalStatus::FOR_APPROVAL,
                ]);

                TicketTeam::create([
                    'ticket_id' => $ticket->id,
                    'team_id' => $this->team != 'undefined' ? $this->team : null
                ]);

                if ($this->fileAttachments) {
                    foreach ($this->fileAttachments as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/$ticket->ticket_number/creation_attachments",
                            $uploadedFile,
                            $fileName
                        );

                        $ticketFile = new TicketFile();
                        $ticketFile->file_attachment = $fileAttachment;
                        $ticketFile->ticket_id = $ticket->id;

                        $ticket->fileAttachments()->save($ticketFile);
                    }
                }

                $approverLevel = ApproverLevel::with('approver.profile')
                    ->withWhereHas(
                        'approver.buDepartments',
                        fn($query) => $query->whereIn('departments.id', auth()->user()->buDepartments()->pluck('departments.id')->toArray())
                    )->withWhereHas(
                        'approver.branches',
                        fn($query) => $query->whereIn('branches.id', auth()->user()->branches()->pluck('branches.id')->toArray())
                    )->get();

                $filteredLevel1Approvers = $approverLevel->where('level_id', Level::where('value', 1)->pluck('value')->first());
                $filteredLevel2Approvers = $approverLevel->where('level_id', Level::where('value', 2)->pluck('value')->first());

                if ($filteredLevel1Approvers->isNotEmpty() && $filteredLevel2Approvers->isNotEmpty()) {
                    if ($ticket->helpTopic->specialProject) {
                        // Filter approver ids by level
                        $level1ApproverIds = $filteredLevel1Approvers->isNotEmpty()
                            ? $filteredLevel1Approvers->pluck('user_id')->toArray()
                            : null;

                        $level2ApproverIds = $filteredLevel2Approvers->isNotEmpty()
                            ? $filteredLevel2Approvers->pluck('user_id')->toArray()
                            : null;

                        TicketApproval::create([
                            'ticket_id' => $ticket->id,
                            'level_1_approver' => [
                                'approver_id' => $level1ApproverIds,
                                'is_approved' => false,
                                'approved_by' => null,
                            ],
                            'level_2_approver' => [
                                'approver_id' => $level2ApproverIds,
                                'is_approved' => false,
                                'approved_by' => null,
                            ],
                        ]);
                    }

                    $filteredLevel1Approvers->each(function ($serviceDepartmentAdmin) use ($ticket) {
                        Notification::send($serviceDepartmentAdmin->approver, new TicketCreatedNotification($ticket));
                        Mail::to($serviceDepartmentAdmin->approver)->send(new TicketCreatedMail($ticket, $serviceDepartmentAdmin->approver));
                    });
                }

                ActivityLog::make($ticket->id, 'created a ticket');
                $this->actionOnSubmit();
                noty()->addSuccess('Ticket successfully created.');
            });
        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong.');
        }
    }

    public function updatedFileAttachments()
    {
        $this->validate([
            'fileAttachments.*' => [
                'nullable',
                File::types(['jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv,txt'])
                    ->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function updatedServiceDepartment()
    {
        $this->helpTopics = HelpTopic::with(['team', 'sla'])->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->serviceDepartment))->get();
        $this->dispatchBrowserEvent('get-help-topics-from-service-department', ['helpTopics' => $this->helpTopics]);
    }

    public function updatedHelpTopic()
    {
        $this->team = Team::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', $this->helpTopic))->pluck('id')->first();
        $this->sla = ServiceLevelAgreement::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', $this->helpTopic))->pluck('id')->first();
    }

    public function cancel()
    {
        $this->reset();
        $this->dispatchBrowserEvent('clear-select-dropdown');
    }

    public function render()
    {
        return view('livewire.requester.ticket.create-ticket', [
            'priorityLevels' => $this->queryPriorityLevels(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'branches' => Branch::where('id', '!=', auth()->user()->branch_id)->get(),
        ]);
    }
}
