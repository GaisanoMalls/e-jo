<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Models\ActivityLog;
use App\Models\ApproverLevel;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Form;
use App\Models\HelpTopic;
use App\Models\Level;
use App\Models\PriorityLevel;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketTeam;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\DB;
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
    public $allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx', 'xlsx', 'xls', 'csv'];

    // Help topic form
    public ?Form $helpTopicForm;
    public $formId;
    public $formName;
    public $formFields = [];
    public $filledForms = []; // Insert the filled forms here.
    public $isHelpTopicHasForm = false;

    protected $listeners = ['clearTicketErrorMessages' => 'clearErrorMessage'];

    public function mount()
    {
        $this->setDefaultPriorityLevel();
    }

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

    private function setDefaultPriorityLevel()
    {
        $this->priorityLevel = (int) PriorityLevel::where('value', 1)->pluck('id')->first();
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
        $this->setDefaultPriorityLevel();
    }

    public function sendTicket()
    {
        if ($this->isHelpTopicHasForm) {
            array_push($this->filledForms, $this->formFields);
        }
        dump($this->filledForms);
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
                    'service_level_agreement_id' => $this->sla,
                    'ticket_number' => $this->generatedTicketNumber(),
                    'subject' => $this->subject,
                    'description' => $this->description,
                    'approval_status' => ApprovalStatusEnum::FOR_APPROVAL,
                ]);

                TicketTeam::create(['ticket_id' => $ticket->id, 'team_id' => $this->team != 'undefined' ? $this->team : null]);

                if ($this->fileAttachments) {
                    collect($this->fileAttachments)->each(function ($uploadedFile) use ($ticket) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/$ticket->ticket_number/creation_attachments", $uploadedFile, $fileName);
                        $ticket->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    });
                }

                $approverLevel = ApproverLevel::with('approver.profile')
                    ->withWhereHas('approver.buDepartments', fn($query) => $query->whereIn('departments.id', auth()->user()->buDepartments()->pluck('departments.id')->toArray()))
                    ->withWhereHas('approver.branches', fn($query) => $query->whereIn('branches.id', auth()->user()->branches()->pluck('branches.id')->toArray()))
                    ->get();

                $filteredLevel1Approvers = $approverLevel->where('level_id', Level::where('value', 1)->pluck('value')->first());
                $filteredLevel2Approvers = $approverLevel->where('level_id', Level::where('value', 2)->pluck('value')->first());

                if ($filteredLevel1Approvers->isNotEmpty()) {
                    if (!is_null($ticket->isSpecialProject())) {
                        // Filter approver ids by level
                        $level1ApproverIds = $filteredLevel1Approvers->isNotEmpty()
                            ? $filteredLevel1Approvers->pluck('user_id')->toArray()
                            : null;

                        $level2ApproverIds = $filteredLevel2Approvers->isNotEmpty()
                            ? $filteredLevel2Approvers->pluck('user_id')->toArray()
                            : null;

                        TicketApproval::create([
                            'ticket_id' => $ticket->id,
                            'approval_1' => [
                                'level_1_approver' => [
                                    'approver_id' => $level1ApproverIds, // array<int>
                                    'approved_by' => null,
                                    'is_approved' => false,
                                ],
                                'level_2_approver' => [
                                    'approver_id' => $level2ApproverIds, // array<int>
                                    'approved_by' => null,
                                    'is_approved' => false,
                                ],
                                'is_all_approved' => false,
                            ],
                            'approval_2' => [
                                'level_1_approver' => [
                                    'approver_id' => $level1ApproverIds, // array<int>
                                    'approved_by' => null,
                                    'is_approved' => false,
                                ],
                                'level_2_approver' => [
                                    'approver_id' => $level2ApproverIds, // array<int>
                                    'approved_by' => null,
                                    'is_approved' => false,
                                ],
                                'is_all_approved' => false,
                            ],
                            'is_all_approval_done' => false
                        ]);
                    }

                    $filteredLevel1Approvers->each(function ($serviceDepartmentAdmin) use ($ticket) {
                        Notification::send(
                            $serviceDepartmentAdmin->approver,
                            new AppNotification(
                                ticket: $ticket,
                                title: "New Ticket {$ticket->ticket_number}",
                                message: "{$ticket->user->profile->getFullName()} created a ticket"
                            )
                        );
                        // Mail::to($serviceDepartmentAdmin->approver)->send(new TicketCreatedMail($ticket, $serviceDepartmentAdmin->approver));
                    });
                }

                if ($this->isHelpTopicHasForm) {
                    array_push($this->filledForms, $this->formFields);
                }

                ActivityLog::make($ticket->id, 'created a ticket');
                $this->actionOnSubmit();
                noty()->addSuccess('Ticket successfully created.');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedFileAttachments()
    {
        $this->validate([
            'fileAttachments.*' => [
                'nullable',
                File::types($this->allowedExtensions)->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function updatedServiceDepartment()
    {
        $this->helpTopics = HelpTopic::with(['team', 'sla'])->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $this->serviceDepartment))->get();
        $this->dispatchBrowserEvent('get-help-topics-from-service-department', ['helpTopics' => $this->helpTopics]);
    }

    public function updatedHelpTopic($value)
    {
        $this->team = Team::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', $this->helpTopic))->pluck('id')->first();
        $this->sla = ServiceLevelAgreement::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', $this->helpTopic))->pluck('id')->first();
        $helpTopicForm = Form::with('fields')->where('help_topic_id', $value)->first(); // Get the help topic form

        if ($helpTopicForm) {
            $this->isHelpTopicHasForm = true;
            $this->helpTopicForm = $helpTopicForm;
            $this->formId = $helpTopicForm->id;
            $this->formName = $helpTopicForm->name;
            $this->formFields = $helpTopicForm->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'variable_name' => $field->variable_name,
                    'is_required' => $field->is_required,
                    'is_enabled' => $field->is_enabled,
                    'value' => null, // To store the value of the given inputs
                    'form' => $this->helpTopicForm->only(['id', 'help_topic_id', 'visible_to', 'editable_to', 'name'])
                ];
            })->toArray();

            $this->description = null; // Not necessary when using custom form.
            $this->helpTopicForm = $helpTopicForm; // Assign the helpTopicForm property of the selected help topic form.
            $this->dispatchBrowserEvent('show-help-topic-forms');
        } else {
            $this->isHelpTopicHasForm = false;
            $this->dispatchBrowserEvent('hide-ticket-description-container');
        }
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
            'branches' => Branch::whereNotIn('id', auth()->user()->branches->pluck('id')->toArray())->get(),
        ]);
    }
}
