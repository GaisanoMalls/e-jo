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
                                        @error('sla')
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
                                            @if ($teams)
                                            <span class="fw-normal" style="font-size: 13px;">
                                                ({{ $teams->count() }})</span>
                                            @endif
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
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label form__field__label">Amount</label>
                                        <input type="text" wire:model="amount"
                                            class="form-control form__field amount__field" id="amount"
                                            placeholder="Enter amount">
                                        @error('amount')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
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
                                <div wire:ignore>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <div class="row" id="editSelectApproverContainer">
                                            </div>
                                        </div>
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

    const slaSelect = document.querySelector('#select-help-topic-sla');
    VirtualSelect.init({
        ele: slaSelect,
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

    // Load approval upon page load.
    const selectApproverContainer = document.querySelector('#editSelectApproverContainer');
    if (levelOfApprovalSelect) {
        window.onload = () => {
            const numberOfApproval = parseInt(levelOfApprovalSelect.value);
            const approvers = @json($approvers);
            const currentApprovers = @json($currentApprovers);

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
                        approvers.forEach((approver) => {
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

                    const levelApprovers = currentApprovers.filter(approver=> approver.level == count);
                    const editLevelOfApproverSelect = document.querySelector(`#level${count}Approver`);

                    if (editLevelOfApproverSelect) {
                        const selectedApprovers = levelApprovers.map(approver => approver.id);
                        editLevelOfApproverSelect.setValue(selectedApprovers);
                    }
                }
            }

            let level1ApproverSelect = document.querySelector(`#level1Approver`);
            if (level1ApproverSelect) {
                level1ApproverSelect.addEventListener('change', () => {
                    @this.set('level1Approvers', level1ApproverSelect.value);
                });
            }
        }
    }

    let level1ApproverSelect = document.querySelector(`#level1Approver`);
    if (level1ApproverSelect) {
        level1ApproverSelect.addEventListener('change', () => {
            @this.set('level1Approvers', level1ApproverSelect.value);
        });
    }

    if (levelOfApprovalSelect) {
        levelOfApprovalSelect.addEventListener('change', () => {
            const levelOfApproval = parseInt(levelOfApprovalSelect.value);
            selectApproverContainer.innerHTML = '';
            const approvers = @json($approvers);

            if (levelOfApproval) {
                @this.set('level_of_approval', levelOfApproval);
                for (let count = 1; count <= levelOfApproval; count++) {
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
                    const levelApproverSelect = document.querySelector(`#level${count}Approver`);

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
                        ele: levelApproverSelect,
                        options: approverOption,
                        showValueAsTags: true,
                        markSearchResults: true,
                        multiple: true,
                        required: true
                    });

                    // Select option by level (Level 1-5)
                    let levelApprover = document.querySelector(`#level${count}Approver`);
                    levelApprover.addEventListener('change', () => {
                        console.log("hello");
                        @this.set(`level${count}Approvers`, levelApprover.value);
                    });
                }
            }
        });
    }

    serviceDepartmentSelect.addEventListener('change', () => {
        const serviceDepartmentId = parseInt(serviceDepartmentSelect.value);
        if (serviceDepartmentId) {
            @this.set('service_department', serviceDepartmentId);
            teamSelect.enable();
            window.addEventListener('get-teams-from-selected-service-department', (event) => {
                const teams = event.detail.teams;
                const teamOption = [];

                if (teams.length > 0) {
                    teams.forEach(function (team) {
                        VirtualSelect.init({
                            ele: teamSelect,
                        });

                        teamOption.push({
                            label: team.name,
                            value: team.id
                        });
                    });
                    teamSelect.setOptions(teamOption);
                } else {
                    teamSelect.setOptions([]);
                    teamSelect.disable();
                }
            });
        } else {
            teamSelect.reset();
            teamSelect.disable()
            teamSelect.setOptions([]);
        }
    });

    slaSelect.addEventListener('reset', () => {
        @this.set('sla', null);
    });

    serviceDepartmentSelect.addEventListener('reset', () => {
        @this.set('service_department', null);
        @this.set('teams', []); // Clear teams count when service department is resetted.
    });

    slaSelect.addEventListener('change', () => {
        const slaId = parseInt(slaSelect.value);
        if (slaId) @this.set('sla', slaId);
    });

    teamSelect.addEventListener('change', () => {
        const teamId = parseInt(teamSelect.value);
        if (teamId) @this.set('team', teamId);
    });

</script>
@endpush