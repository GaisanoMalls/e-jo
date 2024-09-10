<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Enums\FieldTypesEnum;
use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Mail\Requester\TicketCreatedMail;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\FieldRowValue;
use App\Models\Form;
use App\Models\HelpTopic;
use App\Models\PriorityLevel;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketCustomFormField;
use App\Models\TicketTeam;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class CreateTicket extends Component
{
    use Utils, BasicModelQueries, WithFileUploads;

    public ?Collection $helpTopics = null;
    public int $upload = 0;
    public ?string $subject = null;
    public ?string $description = null;
    public ?int $branch = null;
    public ?int $team = null;
    public ?int $sla = null;
    public ?int $priorityLevel = null;
    public ?int $serviceDepartment = null;
    public ?int $helpTopic = null;
    public $fileAttachments = [];
    public array $allowedExtensions = ['jpeg', 'jpg', 'png', 'pdf', 'doc', 'docx', 'xlsx', 'xls', 'csv'];

    // Help topic form
    public ?Form $helpTopicForm = null;
    public ?int $formId = null;
    public ?string $formName = null;
    public bool $isHelpTopicHasForm = false;
    public bool $isHeaderFieldSet = false;
    public bool $isHeaderFieldsHasValues = false;
    public array $formFields = [];
    public array $filledForms = []; // Insert the filled forms here.
    public array $headerFields = [];
    public array $rowFields = [];
    public int $rowCount = 1;

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
        $this->fileAttachments = [];
        $this->upload++;
        $this->emit('loadDashboard');
        $this->emit('loadTicketTab');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('clear-branch-dropdown-select');
        $this->setDefaultPriorityLevel();
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
                    'service_level_agreement_id' => $this->sla,
                    'ticket_number' => $this->generatedTicketNumber(),
                    'subject' => $this->subject,
                    'description' => $this->description ?? null,
                    'approval_status' => ApprovalStatusEnum::FOR_APPROVAL,
                ]);

                TicketTeam::create([
                    'ticket_id' => $ticket->id,
                    'team_id' => $this->team != 'undefined' ? $this->team : null
                ]);

                if (!empty($this->fileAttachments)) {
                    foreach ($this->fileAttachments as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/creation_attachments", $uploadedFile, $fileName);
                        $ticket->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    }
                }

                $approvers = User::withWhereHas('helpTopicApprovals', function ($query) use ($ticket) {
                    $query->withWhereHas('configuration', function ($config) use ($ticket) {
                        $config->with('approvers')
                            ->where('bu_department_id', $ticket->user->buDepartments->pluck('id')->first());
                    });
                })->get();

                $approvers->each(function ($approver) use ($ticket) {
                    $approver->helpTopicApprovals->each(function ($helpTopicApproval) use ($ticket, $approver) {
                        TicketApproval::create([
                            'ticket_id' => $ticket->id,
                            'help_topic_approver_id' => $helpTopicApproval->id,
                        ]);
                    });

                    Notification::send(
                        $approver,
                        new AppNotification(
                            ticket: $ticket,
                            title: "New Ticket {$ticket->ticket_number}",
                            message: "{$ticket->user->profile->getFullName} created a ticket"
                        )
                    );
                    // Mail::to($approver)->send(new TicketCreatedMail($ticket, $approver));
                });

                if ($this->isHelpTopicHasForm) {
                    $this->saveFieldValues();

                    // TO BE REVISED
                    // foreach ($this->filledForms as $fields) {
                    //     foreach ($fields as $field) {
                    //         $ticketCustomFormField = TicketCustomFormField::create([
                    //             'ticket_id' => $ticket->id,
                    //             'form_id' => $field['form']['id'],
                    //             'value' => $field['type'] !== 'file' ? $field['value'] : null,
                    //             'name' => $field['name'],
                    //             'label' => $field['label'],
                    //             'type' => $field['type'],
                    //             'variable_name' => $field['variable_name'],
                    //             'is_required' => $field['is_required'],
                    //             'is_enabled' => $field['is_enabled'],
                    //             'assigned_column' => $field['assigned_column'],
                    //             'is_header_field' => $field['is_header_field'],
                    //         ]);

                    //         if ($field['type'] === 'file' && !is_null($field['value'])) {
                    //             foreach ($field['value'] as $uploadedCustomFile) {
                    //                 $fileName = $uploadedCustomFile->getClientOriginalName();
                    //                 $customFileAttachment = Storage::putFileAs("public/tiket/{$ticket->ticket_number}/custom_form_file", $uploadedCustomFile, $fileName);
                    //                 $ticketCustomFormField->ticketCustomFormFiles()->create(['file_attachment' => $customFileAttachment]);
                    //             }
                    //         }
                    //     }
                    // }
                }

                ActivityLog::make(ticket_id: $ticket->id, description: 'created a ticket');
                $this->actionOnSubmit();
                noty()->addSuccess('Ticket created successfully.');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            \Log::error('Error on line: ', [$e->getLine()]);
        }
    }

    public function saveFieldValues()
    {
        foreach ($this->filledForms as $filledFields) {
            foreach ($filledFields as $field) {
                FieldRowValue::create([
                    'field_id' => $field['id'],
                    'value' => $field['value'],
                    'row' => $field['row']
                ]);
            }
        }
    }

    public function addFieldValues()
    {
        $formFields = array_map(function ($field) {
            if (!isset($field['is_header_field']) || !$field['is_header_field']) {
                $field['row'] = $this->rowCount;
            }
            return $field;
        }, $this->formFields);

        $this->filledForms[] = $formFields;
        $this->rowCount++;

        if (!$this->isHeaderFieldSet) {
            $this->headerFields = array_map(function ($fields) {
                return array_filter($fields, fn($field) => $field['is_header_field']);
            }, $this->filledForms);

            $this->isHeaderFieldSet = true;
        }

        $this->rowFields = array_map(function ($fields) {
            return array_filter($fields, fn($field) => !$field['is_header_field']);
        }, $this->filledForms);

        $this->resetFormFields();
    }

    public function getFilteredRowFields()
    {
        $headers = array_unique(array_column(array_merge(...$this->rowFields), 'name'));

        $filteredFields = [];
        foreach ($headers as $header) {
            $filteredFields[$header] = array_map(function ($fields) use ($header) {
                return array_filter($fields, function ($field) use ($header) {
                    return $field['name'] === $header;
                });
            }, $this->rowFields);
        }

        return ['headers' => $headers, 'fields' => $filteredFields];
    }

    public function removeField(int $fieldKey)
    {
        try {
            // Check if the counts are equal before proceeding
            if (count($this->rowFields) === count($this->filledForms)) {
                // Use array_filter to remove the element more efficiently
                $this->rowFields = array_filter(
                    $this->rowFields,
                    fn($key) => $key !== $fieldKey,
                    ARRAY_FILTER_USE_KEY
                );

                $this->filledForms = array_filter(
                    $this->filledForms,
                    fn($key) => $key !== $fieldKey,
                    ARRAY_FILTER_USE_KEY
                );
            }

        } catch (Throwable $e) {
            AppErrorLog::getError($e->getMessage());
            \Log::error('Error on line: ', [$e->getLine()]);
        }

    }

    public function resetFormFields()
    {
        foreach ($this->formFields as &$field) {
            if (!$field['is_header_field'] && !empty($field['value'])) {
                $field['value'] = '';
            }
        }
    }

    public function updatedFileAttachments(&$value)
    {
        $this->validate([
            'fileAttachments.*' => [
                'nullable',
                File::types($this->allowedExtensions)
                    ->max(25600) //25600 (25 MB)
            ],
        ]);
    }

    public function updatedServiceDepartment($value)
    {
        $this->helpTopics = HelpTopic::with(['team', 'sla'])->whereHas('serviceDepartment', fn($query) => $query->where('service_department_id', $value))->get();
        $this->dispatchBrowserEvent('get-help-topics-from-service-department', ['helpTopics' => $this->helpTopics]);
    }

    public function updatedHelpTopic($value)
    {
        $this->headerFields = [];
        $this->rowFields = [];
        $this->filledForms = [];
        $this->isHeaderFieldSet = false;

        $this->team = Team::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', $value))->pluck('id')->first();
        $this->sla = ServiceLevelAgreement::withWhereHas('helpTopics', fn($helpTopic) => $helpTopic->where('help_topics.id', $value))->pluck('id')->first();
        $helpTopicForm = Form::with('fields')->where('help_topic_id', $value)->first(); // Get the help topic form

        if ($helpTopicForm) {
            foreach ($helpTopicForm->fields as $field) {
                // Iterate through the fields to search for a field whose type is file.
                if ($field->type === 'file') {
                    $this->fileAttachments = []; // Clear the file attachments associated with the ticket
                    $this->dispatchBrowserEvent('hide-ticket-file-attachment-field-container');
                } else {
                    $this->dispatchBrowserEvent('show-ticket-file-attachment-field-container');
                }
            }

            $this->isHelpTopicHasForm = true;
            $this->helpTopicForm = $helpTopicForm;
            $this->formId = $helpTopicForm->id;
            $this->formName = $helpTopicForm->name;

            $this->formFields = $helpTopicForm->fields->map(function ($field) {
                return [
                    'row' => null,
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'variable_name' => $field->variable_name,
                    'is_required' => $field->is_required,
                    'is_enabled' => $field->is_enabled,
                    'value' => null, // To store the value of the given inputs
                    'assigned_column' => $field->assigned_column,
                    'is_header_field' => $field->is_header_field,
                    'is_for_ticket_number' => $field->is_for_ticket_number,
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
