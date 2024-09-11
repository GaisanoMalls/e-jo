@php
    use App\Models\Role;
    use App\Enums\FieldTypesEnum as FieldType;
    use App\Enums\ApprovalStatusEnum as Status;
@endphp

<div class="mb-4">
    @if (
        $this->isRecommendationRequested() &&
            auth()->user()->hasRole(Role::USER))
        @if ($this->isTicketIctRecommendationIsApproved())
            <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                style="font-size: 13px; background-color: #dffdef;">
                <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                The recommendation has been approved
            </div>
        @else
            <div class="mb-4 d-flex flex-wrap gap-2 border-0 flex-row rounded-3 align-items-center justify-content-between p-3"
                style="margin-left: 1px; margin-right: 1px; box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;">
                <span class="border-0 d-flex align-items-center" style="font-size: 0.9rem;">
                    <span class="me-2">
                        <div class="d-flex align-items-center">
                            @if ($ictRecommendationServiceDeptAdmin->requestedByServiceDeptAdmin?->profile->picture)
                                <img src="{{ Storage::url($ictRecommendationServiceDeptAdmin?->requestedByServiceDeptAdmin->profile->picture) }}"
                                    class="image-fluid rounded-circle"
                                    style="height: 26px !important; width: 26px !important;">
                            @else
                                <div class="d-flex align-items-center p-2 me-1 justify-content-center text-white rounded-circle"
                                    style="background-color: #196837; height: 26px !important; width: 26px !important; font-size: 0.7rem;">
                                    {{ $ictRecommendationServiceDeptAdmin->requestedByServiceDeptAdmin?->profile->getNameInitial() }}
                                </div>
                            @endif
                            <strong class="text-muted">
                                {{ $ictRecommendationServiceDeptAdmin->requestedByServiceDeptAdmin?->profile->getFullName }}
                            </strong>
                        </div>
                    </span>
                    is requesting for recommendation approval
                </span>
            </div>
        @endif
    @endif
    <div class="row">
        @if ($customFormHeaderFields->isNotEmpty() && $customFormRowFields->isNotEmpty())
            <div class="col-12">
                <div class="row my-3 mx-auto ps-1 rounded-3 custom__form">
                    <div class="d-flex align-items-center justify-content-between flex-row mb-3">
                        <h6 class="fw-bold mt-2 mb-0 text-end mt-4 form__name">
                            {{ $ticket->helpTopic->form->name }}
                        </h6>
                        <img src="{{ asset('images/gmall-davao-pr-form.png') }}" class="pr__form__gmall__logo mt-3"
                            alt="GMall Ticketing System">
                    </div>
                    @if ($customFormHeaderFields->isNotEmpty())
                        <div class="row mx-auto my-3">
                            @foreach ($customFormHeaderFields as $key => $headerField)
                                @if ($headerField->field->assigned_column == 1)
                                    <div class="col-lg-6 col-md-12 col-sm-12 ps-0 pe-lg-4 pe-md-0 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="form-label mb-0 input__field__label"
                                                style="white-space: nowrap">
                                                {{ $headerField->field->label }}:
                                            </label>
                                            <label class="w-100 header__field">
                                                @if ($headerField->field->type == 'date')
                                                    {{ date('F j, Y', strtotime($headerField->value)) }}
                                                @else
                                                    {{ $headerField->value }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                @if ($headerField->field->assigned_column == 2)
                                    <div class="col-lg-6 col-md-12 col-sm-12 px-0 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="form-label mb-0 input__field__label"
                                                style="white-space: nowrap">
                                                {{ $headerField->field->label }}:
                                            </label>
                                            <label class="w-100 header__field">
                                                @if ($headerField->field->type == 'date')
                                                    {{ date('F j, Y', strtotime($headerField->value)) }}
                                                @else
                                                    {{ $headerField->value }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if ($customFormRowFields->isNotEmpty())
                        <div class="w-100 msx-auto d-flex flex-row flex-xl-nowrap flex-lg-nowrap flex-sm-wrap">
                            @php
                                $filteredRowField = $this->getFilteredRowFields();
                                $headers = $filteredRowField['headers'];
                                $fields = $filteredRowField['fields'];
                            @endphp
                            @if (!empty($headers) && !empty($fields))
                                <div class="w-100">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                @foreach ($headers as $header)
                                                    <th class="input__field__label">
                                                        {{ Str::title($header) }}
                                                    </th>
                                                @endforeach
                                                <th class="input__field__label text-center">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customFormRowFields->toArray() as $rowField)
                                                @dump($rowField)
                                                <tr class="row__field">
                                                    @foreach ($headers as $header)
                                                        {{-- @foreach ($fields as $field)
                                                            @dump($field)
                                                        @endforeach --}}
                                                        {{-- <td class="field__value">
                                                            @if ($field['field']['type'] == 'date')
                                                                {{ date('F j, Y', strtotime($field['value'])) }}
                                                            @else
                                                                {{ $field['value'] ?? '' }}
                                                            @endif
                                                        </td> --}}
                                                    @endforeach
                                                    <td>
                                                        <button wire:click="" type="button"
                                                            class="btn p-0 m-auto d-flex align-items-center justify-content-center gap-1"
                                                            style="font-size: 0.8rem; color: red;">
                                                            <i class="bi bi-trash"></i>
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
