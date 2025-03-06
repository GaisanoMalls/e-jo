<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PredefinedFieldValueEnum;
use App\Http\Requests\Requester\StoreTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Http\Traits\Utils;
use App\Mail\Requester\TicketCreatedMail;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\Form;
use App\Models\HelpTopic;
use App\Models\PriorityLevel;
use App\Models\Role;
use App\Models\ServiceLevelAgreement;
use App\Models\Status;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketCustomFormFooter;
use App\Models\TicketTeam;
use App\Models\User;
use App\Notifications\AppNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
    public bool $doesntHaveApprovalConfig = false;
    public array $serviceDepartmentAdmins = [];
    public array|string $fileAttachments = [];
    public array $allowedExtensions = [
        'jpeg',
        'jpg',
        'png',
        'pdf',
        'doc',
        'docx',
        'xlsx',
        'xls',
        'csv'
    ];

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
    public array $fieldsWithDefaultValues = [];
    public ?string $poNumber = null;

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

    public function cancel()
    {
        $this->reset();
        $this->dispatchBrowserEvent('clear-select-dropdown');
    }

    private function fetchRequesterServiceDepartmentAdmins()
    {
        return User::role([Role::SERVICE_DEPARTMENT_ADMIN])
            ->with('profile')
            ->whereHas('buDepartments', fn($query) => $query->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')))
            ->get();
    }

    private function filterServiceDepartmentAdmins(int $helpTopicId)
    {
        $this->doesntHaveApprovalConfig = HelpTopic::where('id', $helpTopicId)
            ->whereDoesntHave('configurations')
            ->exists();

        if ($this->doesntHaveApprovalConfig) {
            $this->dispatchBrowserEvent('show-requester-service-department-admins', [
                'serviceDepartmentAdmins' => $this->fetchRequesterServiceDepartmentAdmins()
            ]);
        }
    }

    public function updatedServiceDepartment($value)
    {
        $this->helpTopics = HelpTopic::with(['team', 'sla'])
            ->whereHas('serviceDepartment', function ($query) use ($value) {
                $query->where('service_department_id', $value);
            })
            ->get();

        $this->dispatchBrowserEvent('get-help-topics-from-service-department', ['helpTopics' => $this->helpTopics]);
    }

    public function updatedHelpTopic($value)
    {
        $this->helpTopic = $value;
        $this->headerFields = [];
        $this->rowFields = [];
        $this->filledForms = [];
        $this->isHeaderFieldSet = false;

        $this->filterServiceDepartmentAdmins($this->helpTopic);

        $this->team = Team::withWhereHas('helpTopics', fn($helpTopic) =>
            $helpTopic->where('help_topics.id', $value))
            ->pluck('id')
            ->first();

        $this->sla = ServiceLevelAgreement::withWhereHas('helpTopics', fn($helpTopic) =>
            $helpTopic->where('help_topics.id', $value))
            ->pluck('id')
            ->first();

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
                    'config' => $field->config,
                    'form' => $this->helpTopicForm->only(['id', 'help_topic_id', 'visible_to', 'editable_to', 'name'])
                ];
            })->toArray();

            $this->fieldsWithDefaultValues = $this->assignDefaultValues($this->formFields);

            $this->description = null; // Not necessary when using custom form.
            $this->helpTopicForm = $helpTopicForm; // Assign the helpTopicForm property of the selected help topic form.
            $this->dispatchBrowserEvent('show-help-topic-forms');
        } else {
            $this->isHelpTopicHasForm = false;
            $this->dispatchBrowserEvent('hide-ticket-description-container');
        }
    }

    private function assignDefaultValues(array $formFields)
    {
        if (!$this->poNumber) {
            $this->poNumber = $this->generatedTicketNumber();
        }

        $user = auth()->user()->role(Role::USER)->first();

        $fields = array_map(function ($field) use ($user) {
            if ($field['config']['get_value_from']['value'] !== null) {
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::CURRENT_DATE->value) {
                    $field['value'] = Carbon::now()->format('M j, Y');
                }

                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::TICKET_NUMBER->value) {
                    $field['value'] = $this->poNumber;
                }

                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_BRANCH->value) {
                    $user->load('branches');
                    $field['value'] = $user->branches->first()->name;
                }

                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_DEPARTMENT->value) {
                    $user->load('buDepartments');
                    $field['value'] = $user->buDepartments->first()->name;
                }

                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_FULL_NAME->value) {
                    $user->load('profile');
                    $field['value'] = $user->profile->getFullName;
                }

                return [
                    'label' => $field['label'],
                    'value' => $field['value'],
                ];
            }
        }, $formFields);

        return array_filter($fields);
    }

    public function isPredefinedField($formField)
    {
        return ($formField['config']['get_value_from']['label'] !== null && $formField['config']['get_value_from']['value'] !== null)
            && $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::CURRENT_DATE->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::TICKET_NUMBER->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_BRANCH->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_DEPARTMENT->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_FULL_NAME->value;
    }

    private function saveFieldValues(Ticket $ticket)
    {
        foreach ($this->headerFields as $fields) {
            foreach ($fields as $field) {
                FieldHeaderValue::create([
                    'ticket_id' => $ticket->id,
                    'field_id' => $field['id'],
                    'value' => $field['value']
                ]);
            }
        }

        foreach ($this->rowFields as $fields) {
            foreach ($fields as $field) {
                FieldRowValue::create([
                    'ticket_id' => $ticket->id,
                    'field_id' => $field['id'],
                    'value' => $field['value'],
                    'row' => $field['row']
                ]);
            }
        }
    }

    public function addFieldValues()
    {
        $user = auth()->user()->role(Role::USER)->first();
        $rowCount = count($this->filledForms) + 1; // Initialize row count for the new batch

        $fields = array_map(function ($field) use ($user, &$rowCount) {
            if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::CURRENT_DATE->value) {
                $field['value'] = Carbon::now();
            }

            if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::TICKET_NUMBER->value) {
                $field['value'] = $this->poNumber;
            }

            if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_BRANCH->value) {
                $user->load('branches');
                $field['value'] = $user->branches->first()->name;
            }

            if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_DEPARTMENT->value) {
                $user->load('buDepartments');
                $field['value'] = $user->buDepartments->first()->name;
            }

            if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_FULL_NAME->value) {
                $user->load('profile');
                $field['value'] = $user->profile->getFullName;
            }

            // Assign row count for each field in the batch
            $field['row'] = $rowCount;
            return $field;

        }, $this->formFields);

        // Validate required fields
        $validationErrors = [];
        foreach ($fields as $field) {
            if ($field['is_required'] && empty($field['value']) && $field['config']['get_value_from']['value'] === null) {
                $validationErrors[] = "{$field['label']} field is required.";
            }
        }

        if (!empty($validationErrors)) {
            foreach ($validationErrors as $error) {
                session()->flash('custom_form_field_message', $error);
            }
            return;
        }

        $this->filledForms[] = $fields;

        if (!$this->isHeaderFieldSet) {
            $this->headerFields = array_map(function ($fields) {
                return array_filter($fields, fn($field) => $field['is_header_field'] && $field['is_enabled']);
            }, $this->filledForms);

            $this->isHeaderFieldSet = true;
        }

        $this->rowFields = array_map(function ($fields) {
            return array_filter($fields, fn($field) => !$field['is_header_field'] && $field['is_enabled']);
        }, $this->filledForms);

        $this->resetFormFields();
    }

    public function getFilteredRowFields()
    {
        $headers = array_unique(
            array_column(
                array_merge(...$this->rowFields),
                'name'
            )
        );

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

    public function sendTicket()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $ticket = Ticket::create([
                    'user_id' => auth()->user()->id,
                    'branch_id' => $this->branch ?: auth()->user()->branches->pluck('id')->first(),
                    'service_department_id' => $this->serviceDepartment,
                    'help_topic_id' => $this->helpTopic,
                    'status_id' => Status::OPEN,
                    'priority_level_id' => $this->priorityLevel,
                    'service_level_agreement_id' => $this->sla,
                    'ticket_number' => $this->poNumber ?: $this->generatedTicketNumber(),
                    'subject' => $this->subject,
                    'description' => $this->description ?: null,
                    'approval_status' => ApprovalStatusEnum::FOR_APPROVAL,
                ]);

                TicketTeam::create([
                    'ticket_id' => $ticket->id,
                    'team_id' => $this->team != 'undefined' ? $this->team : null
                ]);

                if ($ticket->helpTopic->form) {
                    TicketCustomFormFooter::create([
                        'ticket_id' => $ticket->id,
                        'form_id' => $ticket->helpTopic->form->id,
                        'requested_by' => $ticket->user->id
                    ]);
                }

                if (!empty($this->fileAttachments)) {
                    foreach ($this->fileAttachments as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/creation_attachments", $uploadedFile, $fileName);
                        $ticket->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    }
                }

                if ($this->doesntHaveApprovalConfig) {
                    $ticket->update([
                        'status_id' => Status::APPROVED,
                        'approval_status' => ApprovalStatusEnum::APPROVED
                    ]);

                    $serviceDeptAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                        ->whereIn('id', $this->serviceDepartmentAdmins)
                        ->get();

                    $serviceDeptAdmins->each(function ($serviceDeptAdmin) use ($ticket) {
                        Mail::to($serviceDeptAdmin)->send(new TicketCreatedMail($ticket, $serviceDeptAdmin));
                        Notification::send(
                            $serviceDeptAdmin,
                            new AppNotification(
                                ticket: $ticket,
                                title: "Ticket #{$ticket->ticket_number} (New)",
                                message: "{$ticket->user->profile->getFullName} created a ticket"
                            )
                        );
                    });
                } else {
                    // Filter the approvers that were assigned in the approval configuration
                    $approvers = User::role([Role::SERVICE_DEPARTMENT_ADMIN, Role::APPROVER])
                        ->withWhereHas('helpTopicApprovals', function ($query) use ($ticket) {
                            $query->withWhereHas('configuration', function ($config) use ($ticket) {
                                $config->with('approvers')
                                    ->whereIn('bu_department_id', $ticket->user->buDepartments->pluck('id'));
                            });
                        })->get();

                    if ($approvers->isNotEmpty()) {
                        $approvers->each(function ($approver) use ($ticket) {
                            $approver->helpTopicApprovals->each(function ($helpTopicApproval) use ($ticket) {
                                TicketApproval::create([
                                    'ticket_id' => $ticket->id,
                                    'help_topic_approver_id' => $helpTopicApproval->id,
                                ]);
                            });

                            if ($approver->isServiceDepartmentAdmin()) {
                                Mail::to($approver)->send(new TicketCreatedMail($ticket, $approver));
                                Notification::send(
                                    $approver,
                                    new AppNotification(
                                        ticket: $ticket,
                                        title: "Ticket #{$ticket->ticket_number} (New)",
                                        message: "{$ticket->user->profile->getFullName} created a ticket"
                                    )
                                );
                            }
                        });
                    }
                }

                if ($this->isHelpTopicHasForm) {
                    $this->saveFieldValues($ticket);
                }

                ActivityLog::make(ticket_id: $ticket->id, description: 'created a ticket');
                $this->actionOnSubmit();
                noty()->addSuccess('Ticket created successfully.');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error on line: ', [$e->getLine()]);
        }
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
