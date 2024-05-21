<div>
    <div class="table-responsive custom__table">
        @if ($helpTopics->isNotEmpty())
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Help Topic</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">SLA</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Form</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($helpTopics as $helpTopic)
                        <tr wire:key="help-topic-{{ $helpTopic->id }}">
                            <td>
                                <div
                                    class="d-flex gap-4 justify-content-between align-items-center text-start td__content">
                                    <span>{{ $helpTopic->name }}</span>
                                    @if ($helpTopic->specialProject?->amount)
                                        <div class="d-flex align-items-center rounded-4"
                                            style="background-color: #f1f3ef; padding: 0.1rem 0.4rem;">
                                            <span style="font-size: 11px; color: #D32839;">₱</span>
                                            <span style="font-size: 11px; color: #D32839;">
                                                {{ number_format($helpTopic->specialProject?->amount, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->serviceDepartment?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->team?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->sla?->time_unit }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 text-start td__content">
                                    @if ($helpTopic->forms->isNotEmpty())
                                        <span wire:click="viewHelpTopicForm({{ $helpTopic->id }})"
                                            data-bs-toggle="modal" data-bs-target="#viewFormModal"
                                            class="btn__view__form">View</span>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->dateCreated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->dateUpdated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                        data-tooltip-font-size="11px"
                                        onclick="window.location.href='{{ route('staff.manage.help_topic.edit_details', $helpTopic->id) }}'"
                                        type="button" class="btn action__button">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteHelpTopicModal"
                                        wire:click="deleteHelpTopic({{ $helpTopic->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">No records for help topics.</small>
            </div>
        @endif
    </div>

    {{-- Delete Help Topic Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__help__topic" id="deleteHelpTopicModal"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h6 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h6>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to delete this help topic?
                        </p>
                        <strong>{{ $helpTopicName }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                            wire:click="delete">
                            <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View help topic form --}}
    <div wire:ignore class="modal fade help__topic__modal" id="viewFormModal" tabindex="-1"
        aria-labelledby="viewFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0 mb-3">
                    <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">
                        List of forms
                    </h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                @if ($helpTopicForms)
                    @foreach ($helpTopicForms as $form)
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                <i class="bi bi-journal-text"></i>
                                {{ $form->name }}
                            </div>
                            <button
                                class="btn d-flex align-items-center justify-content-center btn-sm action__button mt-0"
                                wire:click="deleteHelpTopicForm({{ $form->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @endif
                {{-- @if ($formFields->isNotEmpty())
                    @foreach ($formFields as $field)
                        @if ($field->type === \App\Enums\FieldTypesEnum::STRING->value)
                            <div class="mb-2">
                                <label for="{{ $field->variable_name }}" class="form-label form__field__label">
                                    {{ $field->name }}
                                </label>
                                <input type="text" wire:model="{{ $field->variable_name }}"
                                    class="form-control form__field" id="{{ $field->variable_name }}"
                                    placeholder="Enter help topic name">
                                @error('{{ $field->variable_name }}')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                @endif --}}
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#deleteHelpTopicModal').modal('hide');
        });

        window.addEventListener('show-delete-help-topic-modal', () => {
            $('#deleteHelpTopicModal').modal('show');
        });
    </script>
@endpush
