@php
    use App\Enums\FieldTypesEnum as FieldType;
@endphp

<div>
    <button class="btn btn-sm btn__purchase__request" data-bs-toggle="modal" data-bs-target="#ticketCustomFormModal">
        View Purchase Request
    </button>
    <div wire:ignore.self class="modal fade create__ticket__modal" id="ticketCustomFormModal" tabindex="-1"
        aria-labelledby="ticketCustomFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-lg">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="">
                    <h1 class="modal-title modal__title fs-5 px-3">{{ $ticket->helpTopic->form->name }}</h1>
                    <div class="modal-body modal__body">
                        <div class="row">
                            @if ($customFormFields)
                                @foreach ($customFormFields as $key => $field)
                                    {{-- Display those fields that are set to enabled. --}}
                                    @dump($field)
                                    {{-- short text field --}}
                                    {{-- @if ($field['type'] === FieldType::SHORT_ANSWER->value)
                                        <div class="col-md-6 mb-3">
                                            <label for="field-{{ $key }}"
                                                class="form-label input__field__label">
                                                {{ Str::title($field['label']) }}
                                            </label>
                                            <input wire:model="" type="text" value="{{ $field->value }}"
                                                id="field-{{ $key }}" class="form-control input__field"
                                                placeholder="Enter {{ Str::lower($field['label']) }}">
                                            @error('formFields.{{ $key }}.value')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    @endif --}}
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-3">
                        <button type="button" class="btn ticket__modal__button btn__close__ticket__modal"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 ticket__modal__button">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Send Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
