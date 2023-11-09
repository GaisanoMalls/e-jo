<div>
    <div class="row justify-content-center help__topics__section">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 help__topic__details__card">
                <div class="help__topic__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Current Help Topic Setup</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated:
                        {{ $helpTopic->dateUpdated() }}
                    </small>
                </div>
                <form wire:submit.prevent="updateHelpTopic">
                    <input type="hidden" value="{{ $helpTopic->id }}" id="helpTopicID">
                    <div class="row gap-4 help__topic__details__container">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label form__field__label">Name</label>
                                        <input type="text" wire:model="name" class="form-control form__field" id="name"
                                            placeholder="Enter name (required)">
                                        @error('name')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Service Level Agreements
                                            (SLA)</label>
                                        <div>
                                            <div id="select-help-topic-sla" wire:ignore></div>
                                        </div>
                                        @error('suffix')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Service Department</label>
                                        <div>
                                            <div id="select-help-topic-service-department" wire:ignore></div>
                                        </div>
                                        @error('service_department')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">
                                            Team
                                        </label>
                                        <div>
                                            <div id="select-help-topic-team" wire:ignore></div>
                                        </div>
                                        @error('team')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                @if (!is_null($helpTopic->specialProject))
                                <small class="fw-bold my-3">Approvals</small>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Level of Approval</label>
                                        <div>
                                            <div id="select-help-topic-level-of-approval" wire:ignore></div>
                                        </div>
                                        @error('level_of_approval')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <div class="row" id="editSelectApproverContainer">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 my-4">
                                    <small class="fw-bold my-3">Current Approvers</small>
                                    <div class="d-flex flex-column gap-3 mt-3 mb-4">
                                        @foreach ($helpTopic->levels as $level)
                                        <div
                                            class="position-relative bg-light p-3 mt-4 rounded-3 current__approvers__container">
                                            <h6 class="mb-0 level__label">Level {{ $level->value }}</h6>
                                            <div class="d-flex flex-wrap gap-3 mt-3">
                                                @foreach ($levelApprovers as $levelApprover)
                                                @foreach ($approvers as $approver)
                                                @if ($levelApprover->user_id == $approver->id &&
                                                $levelApprover->level_id == $level->id )
                                                <div class="card border-0 shadow-sm p-3 rounded-3">
                                                    <div class="d-flex gap-2">
                                                        @if ($approver->profile->picture)
                                                        <img src="{{ Storage::url($approver->profile->picture) }}"
                                                            alt="" class="approver__image approver__picture">
                                                        @else
                                                        <div
                                                            class="approver__image approver__initial__as__picture d-flex align-items-center justify-content-center">
                                                            {{ $approver->profile->getNameInitial() }}
                                                        </div>
                                                        @endif
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-0 approver__name">
                                                                {{ $approver->profile->getFullName() }}
                                                            </h6>
                                                            <small class="approver__email">{{ $approver->email
                                                                }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                        data-bs-dismiss="modal"
                                        onclick="window.location.href='{{ route('staff.manage.help_topic.index') }}'">Cancel</button>
                                    <button type="submit" class="btn m-0 btn__details btn__send">Save</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
<script>
    const slaOption = [
        @foreach ($serviceLevelAgreements as $sla)
            {
                label: "{{ $sla->time_unit }}",
                value: "{{ $sla->id }}"
            },
        @endforeach
    ];

    const helpTopicSlaSelect = document.querySelector('#select-help-topic-sla');
    VirtualSelect.init({
        ele: helpTopicSlaSelect,
        options: slaOption,
        search: true,
        markSearchResults: true,
        selectedValue: '{{ $helpTopic->service_level_agreement_id }}'
    });

    const serviceDepartmentOption = [
        @foreach ($serviceDepartments as $serviceDepartment)
            {
                label: "{{ $serviceDepartment->name }}",
                value: "{{ $serviceDepartment->id }}"
            },
        @endforeach
    ];

    const serviceDepartmentSelect = document.querySelector('#select-help-topic-service-department');
    VirtualSelect.init({
        ele: serviceDepartmentSelect,
        options: serviceDepartmentOption,
        search: true,
        markSearchResults: true,
        selectedValue: '{{ $helpTopic->service_department_id }}'
    });

    const teamOption = [
        @foreach ($teams as $team)
            {
                label: "{{ $team->name }}",
                value: "{{ $team->id }}"
            },
        @endforeach
    ];

    const teamSelect = document.querySelector('#select-help-topic-team');
    VirtualSelect.init({
        ele: teamSelect,
        options: teamOption,
        search: true,
        markSearchResults: true,
        selectedValue: '{{ $helpTopic->team_id }}'
    });

    const levelOfApprovalOption = [
        @foreach ($levelOfApprovals as $approval)
            {
                label: "{{ $approval->description }}",
                value: "{{ $approval->value }}"
            },
        @endforeach
    ];

    const levelOfApprovalSelect = document.querySelector('#select-help-topic-level-of-approval');
    VirtualSelect.init({
        ele: levelOfApprovalSelect,
        options: levelOfApprovalOption,
        search: true,
        markSearchResults: true,
        selectedValue: {{ $helpTopic->levels->pluck('id')->last() ?? 0 }}
    });

    window.onload = () => {
        const numberOfApproval = parseInt(levelOfApprovalSelect.value);
        const selectApproverContainer = document.querySelector('#editSelectApproverContainer');
        const approvers = {!! json_encode($approvers) !!};

        if (numberOfApproval) {
            for (let count = 1; count <= numberOfApproval; count++) {
                const approverOption = [];
                const selectOptionHTML = `
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label form__field__label">Level ${count} approver/s</label>
                            <div>
                                <div wire:ignore id="level${count}Approver" placeholder="Choose an approver"></div>
                            </div>
                            @error('level${count}Approver')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>`;

                selectApproverContainer.insertAdjacentHTML('beforeend', selectOptionHTML);

                if (approvers.length > 0) {
                    approvers.forEach(function (approver) {
                        const middleName = `${approver.profile.middle_name ?? ''}`;
                        const firstLetter = middleName.length > 0 ? middleName[0] + '.' : '';

                        approverOption.push({
                            value: approver.id,
                            label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                        });
                    });
                }

                VirtualSelect.init({
                    ele: `#level${count}Approver`,
                    options: approverOption,
                    showValueAsTags: true,
                    markSearchResults: true,
                    multiple: true,
                    required: true,
                });

                const selectedCurrentApprovers = [];
                const currentApprovers = {!! json_encode($currentApprovers) !!};
                const editLevelOfApproverSelect = document.querySelector(`#level${count}Approver`);

                approverOption.forEach(function (approver) {
                    currentApprovers.forEach(function(currentApprover) {
                        if (approver.value == currentApprover.id && currentApprover.level == count) {
                            // Put the approvers into the array with its level number where they belong.
                            selectedCurrentApprovers.push({
                                id: currentApprover.id,
                                level: currentApprover.level
                            });

                            editLevelOfApproverSelect.setValue(currentApprover.id);
                        }
                    });
                });
            }
        }
    }


</script>
@endpush