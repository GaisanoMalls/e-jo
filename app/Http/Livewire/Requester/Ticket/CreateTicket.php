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
    public array $selectedNonConfigApprovers = [];
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

    /**
     * Clears all validation error messages.
     * 
     * Resets the validation state of the component, removing any displayed error messages.
     * This is typically used to manually clear form validation errors.
     *
     * @return void
     */
    public function clearErrorMessage()
    {
        $this->resetValidation();
    }

    /**
     * Sets the default priority level for the ticket.
     * 
     * Retrieves and assigns the priority level with value '1' (typically the lowest/normal priority)
     * from the PriorityLevel model and sets it as the default priorityLevel property.
     * This ensures all new tickets have a consistent starting priority level.
     *
     * @return void
     * @uses \App\Models\PriorityLevel For priority level lookup
     */
    private function setDefaultPriorityLevel()
    {
        $this->priorityLevel = (int) PriorityLevel::where('value', 1)->pluck('id')->first();
    }

    /**
     * Performs comprehensive cleanup and UI reset after form submission.
     *
     * Handles post-submission tasks including:
     * 1. Form state reset:
     *    - Resets all component properties
     *    - Clears validation errors
     *    - Resets file attachments array
     *    - Increments upload counter (forces UI refresh)
     * 2. UI updates:
     *    - Emits events to refresh dashboard and ticket tab
     *    - Closes the active modal
     *    - Clears branch dropdown selection
     * 3. Defaults restoration:
     *    - Sets default priority level
     *
     * @return void
     * @fires loadDashboard Refreshes dashboard data
     * @fires loadTicketTab Refreshes ticket tab content
     * @dispatches close-modal Browser event
     * @dispatches clear-branch-dropdown-select Browser event
     * @uses setDefaultPriorityLevel() To restore default priority
     */
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

    /**
     * Resets the form and clears any dropdown selections.
     * 
     * Performs two cleanup actions:
     * 1. Resets all component properties to their initial state
     * 2. Dispatches browser event to clear any select dropdown values
     *
     * This is typically called when canceling out of a form or modal.
     *
     * @return void
     * @dispatches clear-select-dropdown Browser event
     */
    public function cancel()
    {
        $this->reset();
        $this->dispatchBrowserEvent('clear-select-dropdown');
    }

    /**
     * Retrieves approvers from the same BU departments as the current user who aren't in approval configurations.
     *
     * Fetches users with either Service Department Admin or Approver roles who:
     * 1. Belong to the same business unit departments as the current user
     * 2. Includes their profile, roles, and department relationships
     * 3. Are not already configured as approvers (implied by method name)
     *
     * @return \Illuminate\Database\Eloquent\Collection Returns collection of User models with:
     *         - Profile data
     *         - Role assignments
     *         - Department relationships
     *         Returns empty collection if no matching users found
     *
     * @uses \App\Models\User For user data
     * @uses \App\Models\Role For role checking
     * @uses with() For eager loading relationships
     * @uses whereHas() For department filtering
     */
    private function fetchNonConfigApprovers()
    {
        return User::role([Role::SERVICE_DEPARTMENT_ADMIN, Role::APPROVER])
            ->with(['profile', 'roles', 'buDepartments'])
            ->whereHas('buDepartments', fn($query) => $query->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')))
            ->get();
    }

    /**
     * Filters and retrieves service department admins without approval configurations.
     *
     * Checks if the specified help topic lacks approval configurations and:
     * 1. Sets a flag indicating missing configurations ($doesntHaveApprovalConfig)
     * 2. If no configurations exist, dispatches browser event with:
     *    - Available approvers from same departments
     *    - Matching required roles (Service Department Admin/Approver)
     *
     * @param int $helpTopicId The ID of the help topic to check configurations for
     * @return void
     * @dispatches fetch-nonconfig-approvers Browser event When configurations are missing
     * @uses fetchNonConfigApprovers() To get eligible approvers
     * @uses \App\Models\HelpTopic For configuration check
     */
    private function filterServiceDepartmentAdmins(int $helpTopicId)
    {
        $this->doesntHaveApprovalConfig = HelpTopic::where('id', $helpTopicId)
            ->whereDoesntHave('configurations')
            ->exists();

        if ($this->doesntHaveApprovalConfig) {
            $this->dispatchBrowserEvent('fetch-nonconfig-approvers', [
                'nonConfigApprovers' => $this->fetchNonConfigApprovers()
            ]);
        }
    }

    /**
     * Handles service department selection changes by loading related help topics.
     * 
     * When the service department value changes, this method:
     * 1. Fetches all help topics belonging to the selected service department
     *    - Includes eager-loaded team and SLA relationships
     * 2. Stores the results in the $helpTopics property
     * 3. Dispatches a browser event with the fetched help topics
     *
     * @param mixed $value The ID of the selected service department
     * @return void
     * @dispatches get-help-topics-from-service-department Browser event With fetched help topics
     * @uses \App\Models\HelpTopic For help topic data
     * @uses with() For eager loading team and SLA relationships
     * @uses whereHas() For service department filtering
     */
    public function updatedServiceDepartment($value)
    {
        $this->helpTopics = HelpTopic::with(['team', 'sla'])
            ->whereHas('serviceDepartment', function ($query) use ($value) {
                $query->where('service_department_id', $value);
            })
            ->get();

        $this->dispatchBrowserEvent('get-help-topics-from-service-department', ['helpTopics' => $this->helpTopics]);
    }

    /**
     * Handles help topic selection changes by resetting form state and loading related data.
     *
     * When a help topic is selected, this method:
     * 1. Resets all form fields and state variables
     * 2. Filters service department admins for the selected help topic
     * 3. Loads associated team and SLA information
     * 4. Processes the help topic's custom form (if exists):
     *    - Handles file attachment fields specially
     *    - Maps all form fields with their configurations
     *    - Assigns default values to fields
     *    - Manages form visibility states
     * 5. Dispatches appropriate browser events for UI updates
     *
     * @param int $value The ID of the selected help topic
     * @return void
     *
     * @fires filterServiceDepartmentAdmins To check approver configurations
     * @dispatches hide/show-ticket-file-attachment-field-container Browser events
     * @dispatches show-help-topic-forms Browser event when form exists
     * @dispatches hide-ticket-description-container Browser event when no form exists
     *
     * @uses \App\Models\Team For team data
     * @uses \App\Models\ServiceLevelAgreement For SLA data
     * @uses \App\Models\Form For custom form handling
     * @uses assignDefaultValues() For field value initialization
     */
    public function updatedHelpTopic($value)
    {
        // Set the selected help topic ID
        $this->helpTopic = $value;

        // Reset all form field collections
        $this->headerFields = [];
        $this->rowFields = [];
        $this->filledForms = [];
        $this->isHeaderFieldSet = false;

        // Filter service department admins for the selected help topic
        $this->filterServiceDepartmentAdmins($this->helpTopic);

        // Get the team associated with the selected help topic
        $this->team = Team::withWhereHas('helpTopics', fn($helpTopic) =>
            $helpTopic->where('help_topics.id', $value))
            ->pluck('id') // Get only the ID
            ->first(); // Take the first result

        // Get the SLA associated with the selected help topic
        $this->sla = ServiceLevelAgreement::withWhereHas('helpTopics', fn($helpTopic) =>
            $helpTopic->where('help_topics.id', $value))
            ->pluck('id') // Get only the ID
            ->first(); // Take the first result

        // Get the form and its fields for the selected help topic
        $helpTopicForm = Form::with('fields')->where('help_topic_id', $value)->first(); // Get the help topic form

        // Check if form exists for this help topic
        if ($helpTopicForm) {
            // Process each field in the form
            foreach ($helpTopicForm->fields as $field) {
                // Handle file type fields specially
                if ($field->type === 'file') {
                    // Clear existing file attachments
                    $this->fileAttachments = [];
                    // Hide file attachment UI
                    $this->dispatchBrowserEvent('hide-ticket-file-attachment-field-container');
                } else {
                    // Show regular field container
                    $this->dispatchBrowserEvent('show-ticket-file-attachment-field-container');
                }
            }

            // Set form flags and properties
            $this->isHelpTopicHasForm = true;
            $this->helpTopicForm = $helpTopicForm;
            $this->formId = $helpTopicForm->id;
            $this->formName = $helpTopicForm->name;

            // Map form fields to our required structure
            $this->formFields = $helpTopicForm->fields->map(function ($field) {
                return [
                    'row' => null, // Row number (null for header fields)
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'variable_name' => $field->variable_name,
                    'is_required' => $field->is_required,
                    'is_enabled' => $field->is_enabled,
                    'value' => null,  // Will be populated with user input
                    'assigned_column' => $field->assigned_column,
                    'is_header_field' => $field->is_header_field,
                    'config' => $field->config,
                    // Include minimal form info for reference
                    'form' => $this->helpTopicForm->only(['id', 'help_topic_id', 'visible_to', 'editable_to', 'name'])
                ];
            })->toArray(); // Convert collection to array

            // Assign default values to fields where configured
            $this->fieldsWithDefaultValues = $this->assignDefaultValues($this->formFields);

            // Clear description field when using custom form
            $this->description = null;

            // Store the form reference again (redundant but ensures consistency)
            $this->helpTopicForm = $helpTopicForm;

            // Show the form UI
            $this->dispatchBrowserEvent('show-help-topic-forms');
        } else {
            // No form exists for this help topic
            $this->isHelpTopicHasForm = false;

            // Hide description container since we're not using custom form
            $this->dispatchBrowserEvent('hide-ticket-description-container');
        }
    }

    /**
     * Assigns default values to form fields based on predefined configurations.
     *
     * Processes an array of form fields and automatically populates values for fields
     * configured with predefined value sources. Supported predefined values include:
     * 1. Current date
     * 2. Generated ticket number
     * 3. User's branch
     * 4. User's department
     * 5. User's full name
     *
     * Also ensures a ticket number (poNumber) is generated if not already set.
     *
     * @param array $formFields Array of form field definitions with configurations
     * @return array Processed fields with default values populated where applicable
     *              Contains only fields with valid labels and values
     *
     * @uses \App\Enums\PredefinedFieldValueEnum For predefined value types
     * @uses \Illuminate\Support\Carbon For current date handling
     * @uses generatedTicketNumber() For ticket number generation
     * @see updatedHelpTopic() The primary consumer of this method
     */
    private function assignDefaultValues(array $formFields)
    {
        // Check if PO number is not already set
        if (!$this->poNumber) {
            // Generate a new ticket number if PO number is empty
            $this->poNumber = $this->generatedTicketNumber();
        }

        // Get the authenticated user with USER role
        $user = User::role(Role::USER)->find(auth()->user()->id);
        // Process each form field to assign default values
        $fields = array_map(function ($field) use ($user) {
            // Check if field has predefined value configuration
            if (isset($field['config']['get_value_from']['value'])) {
                // Handle CURRENT_DATE predefined value
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::CURRENT_DATE->value) {
                    // Set field value to current date formatted
                    $field['value'] = Carbon::now()->format('F j, Y');
                }
                // Handle TICKET_NUMBER predefined value
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::TICKET_NUMBER->value) {
                    // Set field value to the generated PO number
                    $field['value'] = $this->poNumber;
                }
                // Handle USER_BRANCH predefined value
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_BRANCH->value) {
                    // Load user's branches relationship
                    $user->load('branches');
                    // Set field value to user's first branch name
                    $field['value'] = $user->branches->first()->name;
                }
                // Handle USER_DEPARTMENT predefined value
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_DEPARTMENT->value) {
                    // Load user's business unit departments relationship
                    $user->load('buDepartments');
                    // Set field value to user's first department name
                    $field['value'] = $user->buDepartments->first()->name;
                }
                // Handle USER_FULL_NAME predefined value
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_FULL_NAME->value) {
                    // Load user's profile relationship
                    $user->load('profile');
                    // Set field value to user's full name from profile
                    $field['value'] = $user->profile->getFullName;
                }
            }
            // Return processed field with only label and values
            return [
                'label' => $field['label'], // Original field label
                'value' => $field['value'], // Processed field value
            ];
        }, $formFields); // Apply to all fields in formFields array

        return array_filter($fields);
    }

    /**
     * Saves all custom field values to the database for a given ticket.
     *
     * Processes and stores two types of field values:
     * 1. Header fields (single-instance fields):
     *    - Creates FieldHeaderValue records
     *    - Stores field ID and value with ticket association
     * 2. Row fields (multi-instance fields):
     *    - Creates FieldRowValue records
     *    - Stores field ID, value, and row number with ticket association
     *
     * @param \App\Models\Ticket $ticket The ticket to associate field values with
     * @return void
     *
     * @uses \App\Models\FieldHeaderValue For storing header field values
     * @uses \App\Models\FieldRowValue For storing row field values
     * @throws \Exception If database operations fail
     */
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

    /**
     * Determines if a form field is configured with a predefined value source.
     *
     * Checks if a form field has configuration for automatic value assignment by verifying:
     * 1. The field has both label and value configuration in 'get_value_from'
     * 2. The value matches any of the supported predefined types:
     *    - CURRENT_DATE
     *    - TICKET_NUMBER
     *    - USER_BRANCH
     *    - USER_DEPARTMENT
     *    - USER_FULL_NAME
     *
     * @param array $formField The form field definition to check
     * @return bool Returns true if the field is configured with any predefined value source,
     *              false otherwise
     *
     * @uses \App\Enums\PredefinedFieldValueEnum For predefined value type constants
     */
    public function isPredefinedField($formField)
    {
        return (isset($formField['config']['get_value_from']['label']) && isset($formField['config']['get_value_from']['value']))
            && $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::CURRENT_DATE->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::TICKET_NUMBER->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_BRANCH->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_DEPARTMENT->value
            || $formField['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_FULL_NAME->value;
    }

    /**
     * Processes and adds field values to the form, handling predefined values and validation.
     *
     * This method:
     * 1. Processes form fields to automatically populate predefined values (dates, user info, etc.)
     * 2. Validates required fields
     * 3. Organizes fields into header and row field collections
     * 4. Maintains form state and validation messages
     *
     * Key operations:
     * - Auto-populates values for fields configured with:
     *   - Current date
     *   - Ticket number
     *   - User branch/department
     *   - User full name
     * - Validates required fields
     * - Separates header fields from row fields
     * - Manages form state via filledForms, headerFields, and rowFields properties
     * - Handles validation errors with flash messages
     *
     * @return void
     * @throws \Exception If user data loading fails
     *
     * @uses \App\Enums\PredefinedFieldValueEnum For predefined value types
     * @uses \Illuminate\Support\Carbon For date handling
     * @uses resetFormFields() To clear field values after processing
     */
    public function addFieldValues()
    {
        // Get the current authenticated user with USER role
        $user = User::role(Role::USER)->find(auth()->user()->id);

        // Calculate next row number (1 more than current filled forms count)
        $rowCount = count($this->filledForms) + 1;

        // Process each form field to populate values
        $fields = array_map(function ($field) use ($user, &$rowCount) {
            // Check if field has predefined value configuration
            if (isset($field['config']['get_value_from']['value'])) {
                // Handle CURRENT_DATE type fields
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::CURRENT_DATE->value) {
                    $field['value'] = Carbon::now(); // Set to current datetime
                }
                // Handle TICKET_NUMBER type fields  
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::TICKET_NUMBER->value) {
                    $field['value'] = $this->poNumber; // Use generated PO number
                }
                // Handle USER_BRANCH type fields
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_BRANCH->value) {
                    $user->load('branches'); // Eager load branches
                    $field['value'] = $user->branches->first()->name; // Get first branch name
                }
                // Handle USER_DEPARTMENT type fields
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_DEPARTMENT->value) {
                    $user->load('buDepartments'); // Eager load departments
                    $field['value'] = $user->buDepartments->first()->name; // Get first department name
                }
                // Handle USER_FULL_NAME type fields
                if ($field['config']['get_value_from']['value'] === PredefinedFieldValueEnum::USER_FULL_NAME->value) {
                    $user->load('profile'); // Eager load profile
                    $field['value'] = $user->profile->getFullName; // Get user's full name
                }
            }

            // Assign current row number to the field
            $field['row'] = $rowCount;
            return $field;

        }, $this->formFields);

        // Validate required fields
        $validationErrors = [];
        foreach ($fields as $field) {
            // Check if field is required but empty and has predefined value configuration
            if ($field['is_required'] && empty($field['value']) && isset($field['config']['get_value_from']['value'])) {
                $validationErrors[] = "{$field['label']} field is required.";
            }
        }

        // If validation errors exist
        if (!empty($validationErrors)) {
            // Flash each error message to session
            foreach ($validationErrors as $error) {
                session()->flash('custom_form_field_message', $error);
            }
            return; // Exit early if validation fails
        }

        // Add processed fields to filledForms array
        $this->filledForms[] = $fields;

        // Process header fields only once
        if (!$this->isHeaderFieldSet) {
            $this->headerFields = array_map(function ($fields) {
                // Filter for enabled header fields only
                return array_filter($fields, fn($field) => $field['is_header_field'] && $field['is_enabled']);
            }, $this->filledForms); // Mark header fields as processed

            $this->isHeaderFieldSet = true;
        }

        // Process row fields (non-header fields)
        $this->rowFields = array_map(function ($fields) {
            // Filter for enabled non-header fields only
            return array_filter($fields, fn($field) => !$field['is_header_field'] && $field['is_enabled']);
        }, $this->filledForms);

        // Reset form fields for next input
        $this->resetFormFields();
    }

    /**
     * Processes row fields into a structured format for display.
     *
     * Transforms the row-based field data into a columnar structure organized by field names.
     * Returns an array containing:
     * - headers: Unique list of all field names
     * - fields: Array grouped by field name with all corresponding values
     *
     * @return array Associative array with two keys:
     *              - 'headers' => array of unique field names
     *              - 'fields' => array grouped by field name containing all values
     */
    public function getFilteredRowFields()
    {
        // Extract all unique field names to use as headers
        $headers = array_unique(
            array_column(
                // Flatten the multi-dimensional rowFields array
                array_merge(...$this->rowFields),
                // Extract just the 'name' column from each field
                'name'
            )
        );

        // Initialize empty array to hold our filtered results
        $filteredFields = [];

        // Organize fields by their header/name
        foreach ($headers as $header) {
            $filteredFields[$header] = array_map(function ($fields) use ($header) {
                // Filter each row's fields to only include those matching current header
                return array_filter($fields, function ($field) use ($header) {
                    return $field['name'] === $header;
                });
            }, $this->rowFields);
        }

        // Return structured data for display
        return ['headers' => $headers, 'fields' => $filteredFields];
    }

    /**
     * Removes a specific field from both rowFields and filledForms arrays.
     *
     * Safely removes a field by its key from both collections while maintaining array integrity.
     * Performs the removal only if both collections have the same count (data consistency check).
     * Includes error handling to log any exceptions that occur during the removal process.
     *
     * @param int $fieldKey The array key/index of the field to remove
     * @return void
     * @throws \Throwable Captures and logs any errors during removal
     * @uses AppErrorLog For error tracking
     * @uses \Log For error logging
     */
    public function removeField(int $fieldKey)
    {
        try {
            // Only proceed if rowFields and filledForms are in sync (same count)
            if (count($this->rowFields) === count($this->filledForms)) {
                // Remove the field from rowFields using array key filtering
                $this->rowFields = array_filter(
                    $this->rowFields,                            // The array to filter
                    fn($key) => $key !== $fieldKey,      // Keep all keys except the one to remove
                    ARRAY_FILTER_USE_KEY                          // Filter by array keys instead of values
                );

                // Similarly remove from filledForms to maintain consistency
                $this->filledForms = array_filter(
                    $this->filledForms,                          // The array to filter
                    fn($key) => $key !== $fieldKey,      // Keep all keys except the one to remove
                    ARRAY_FILTER_USE_KEY                          // Filter by array keys instead of values
                );
            }

        } catch (Throwable $e) {
            // Log the error message to AppErrorLog
            AppErrorLog::getError($e->getMessage());
            // Also log the line number where error occurred
            \Log::error('Error on line: ', [$e->getLine()]);
        }
    }

    /**
     * Resets non-header form fields by clearing their values.
     *
     * Iterates through form fields and clears the values of:
     * - All non-header fields (where is_header_field = false)
     * - Only fields that currently have values (non-empty)
     * Preserves header fields and their values.
     *
     * @return void
     */
    public function resetFormFields()
    {
        // Iterate through all form fields by reference to allow modification
        foreach ($this->formFields as &$field) {
            // Check if field is NOT a header field AND has a non-empty value
            if (!$field['is_header_field'] && !empty($field['value'])) {
                // Clear the field's value while preserving other properties
                $field['value'] = '';
            }
        }
        // Note: The &$field reference is automatically undone after loop completion
    }

    /**
     * Creates and submits a new ticket with all related data.
     * 
     * Handles the complete ticket creation workflow including:
     * 1. Validation of input data
     * 2. Database transaction for data integrity
     * 3. Ticket creation with all related records:
     *    - Basic ticket info
     *    - Team assignment
     *    - Custom form linking
     *    - File attachments
     * 4. Approval workflow handling:
     *    - Automatic approval for non-configured tickets
     *    - Normal approval process for configured tickets
     * 5. Notifications to relevant parties
     * 6. Activity logging
     * 
     * @return void
     * @throws \Exception On database or processing errors (handled internally)
     * @uses \App\Models\Ticket Main ticket model
     * @uses \App\Enums\ApprovalStatusEnum For approval status values
     * @uses \App\Notifications\AppNotification For in-app notifications
     * @uses \App\Mail\TicketCreatedMail For email notifications
     */
    public function sendTicket()
    {
        // Validate form inputs before processing
        $this->validate();

        try {
            // Wrap all operations in a database transaction
            DB::transaction(function () {
                // Create the main ticket record
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

                // Assign team to the ticket
                TicketTeam::create([
                    'ticket_id' => $ticket->id,
                    'team_id' => $this->team != 'undefined' ? $this->team : null
                ]);

                // Handle custom form linking if help topic has a form
                if ($ticket->helpTopic->form) {
                    TicketCustomFormFooter::create([
                        'ticket_id' => $ticket->id,
                        'form_id' => $ticket->helpTopic->form->id,
                        'requested_by' => $ticket->user->id
                    ]);
                }

                // Process file attachments if any
                if (!empty($this->fileAttachments)) {
                    foreach ($this->fileAttachments as $uploadedFile) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs("public/ticket/{$ticket->ticket_number}/creation_attachments", $uploadedFile, $fileName);
                        $ticket->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    }
                }

                // Handle approval workflow
                if ($this->doesntHaveApprovalConfig) {
                    // Auto-approve if no approval config exists
                    $ticket->update([
                        'status_id' => Status::APPROVED,
                        'approval_status' => ApprovalStatusEnum::APPROVED,
                        'svcdept_date_approved' => Carbon::now()
                    ]);

                    // Notify non-configured approvers
                    $nonConfigApprovers = User::role([Role::SERVICE_DEPARTMENT_ADMIN, Role::APPROVER])
                        ->whereIn('id', $this->selectedNonConfigApprovers)
                        ->get();

                    $nonConfigApprovers->each(function ($nonConfigApprover) use ($ticket) {
                        Mail::to($nonConfigApprover)->send(new TicketCreatedMail($ticket, $nonConfigApprover));
                        Notification::send(
                            $nonConfigApprover,
                            new AppNotification(
                                ticket: $ticket,
                                title: "Ticket #{$ticket->ticket_number} (New)",
                                message: "{$ticket->user->profile->getFullName} created a ticket"
                            )
                        );
                    });
                } else {
                    // Normal approval process for configured tickets
                    $approvers = User::role([Role::SERVICE_DEPARTMENT_ADMIN, Role::APPROVER])
                        ->withWhereHas('helpTopicApprovals.configuration', function ($config) use ($ticket) {
                            $config->whereIn('bu_department_id', $ticket->user->buDepartments->pluck('id'))
                                ->whereIn('branch_id', $ticket->user->branches->pluck('id'));
                        })->get();

                    if ($approvers->isNotEmpty()) {
                        $approvers->each(function ($approver) use ($ticket) {
                            // Create approval records
                            $approver->helpTopicApprovals()
                                ->whereHas('configuration', fn($config) => $config->whereIn('branch_id', $ticket->user->branches->pluck('id')))
                                ->each(function ($helpTopicApproval) use ($ticket) {
                                    TicketApproval::create([
                                        'ticket_id' => $ticket->id,
                                        'help_topic_approver_id' => $helpTopicApproval->id,
                                    ]);
                                });

                            // Notify approvers
                            if ($approver->isServiceDepartmentAdmin() || $approver->isApprover()) {
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

                // Save custom form field values if help topic has form
                if ($this->isHelpTopicHasForm) {
                    $this->saveFieldValues($ticket);
                }

                // Log the ticket creation activity
                ActivityLog::make(ticket_id: $ticket->id, description: 'created a ticket');

                // Perform post-submission cleanup
                $this->actionOnSubmit();

                // Show success notification
                noty()->addSuccess('Ticket created successfully.');
            });
        } catch (Exception $e) {
            // Log any errors that occur
            AppErrorLog::getError($e->getMessage());
            Log::error('Error on line: ', [$e->getLine()]);
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.create-ticket', [
            'priorityLevels' => $this->queryPriorityLevels(),
            'serviceDepartments' => $this->queryServiceDepartments(),
            'branches' => Branch::whereNotIn('id', auth()->user()->branches->pluck('id'))->get(),
        ]);
    }
}
