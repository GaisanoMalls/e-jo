<div>
    <div wire:ignore.self class="modal fade help__topic__modal" id="addNewHelpTopicModal" tabindex="-1"
        aria-labelledby="addNewHelpTopicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">Add new help topic</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveHelpTopic">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="form-check" style="white-space: nowrap;">
                                            <input wire:model="checked" wire:change="specialProject"
                                                class="form-check-input check__special__project" type="checkbox"
                                                role="switch" id="specialProjectCheck" wire:loading.attr="disabled">
                                            <label class="form-check-label" for="specialProjectCheck">
                                                Check if the help topic is a special project
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label for="specialProjectName"
                                                class="form-label form__field__label">Name</label>
                                            <input type="text" wire:model="name" class="form-control form__field"
                                                id="specialProjectName" placeholder="Enter help topic name">
                                            @error('name')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label for="sla" class="form-label form__field__label">
                                                Serice Level Agreement (SLA)
                                            </label>
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
                                        <div class="mb-2">
                                            <label for="department" class="form-label form__field__label">
                                                Service Department
                                            </label>
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
                                        <div class="mb-2">
                                            <label for="team" class="form-label form__field__label">
                                                Team
                                                @if ($teams)
                                                <span class="fw-normal" style="font-size: 13px;">
                                                    ({{ $teams->count() }})</span>
                                                @endif
                                            </label>
                                            <div>
                                                <div id="select-help-topic-team" placeholder="Select (optional)"
                                                    wire:ignore></div>
                                            </div>
                                            @error('team')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div wire:ignore class="mt-2" id="specialProjectContainer">
                                        <div class="py-2">
                                            <hr>
                                        </div>
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
                                        <small class="fw-bold">Approvals</small>
                                        <div class="col-md-4 mt-2">
                                            <div class="mb-2">
                                                <label class="form-label form__field__label">
                                                    Level of approval
                                                </label>
                                                <div>
                                                    <div id="select-help-topic-level-of-approval" wire:ignore></div>
                                                </div>
                                                @error('level_of_approval')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="mb-2">
                                                <div>
                                                    <div wire:ignore class="row" id="selectApproverContainer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                                <span wire:loading wire:target="saveHelpTopic" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Add new
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="cancel">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('extra')
<script>
    const specialProjectContainer = document.getElementById('specialProjectContainer');
    const specialProjectCheck = document.getElementById('specialProjectCheck');
    const specialProjectName = document.getElementById('specialProjectName');
    const levelOfApprovalSelect = document.querySelector('#select-help-topic-level-of-approval');

    const levelOfApprovalOption = [
        @foreach ($levelOfApprovals as $approval)
        {
            label: "{{ $approval->description }}",
            value: "{{ $approval->value }}"
        },
        @endforeach
    ];

    VirtualSelect.init({
        ele: levelOfApprovalSelect,
        options: levelOfApprovalOption,
        search: true,
        markSearchResults: true,
        required: true
    });

    if (specialProjectCheck && specialProjectContainer) {
        specialProjectContainer.style.display = specialProjectCheck.checked ? 'block' : 'none';
        const name = 'Special Project';

        specialProjectCheck.addEventListener('change', () => {
            if (specialProjectCheck.checked) {
                window.addEventListener('show-special-project-container', (event) => {
                    specialProjectContainer.style.display = 'block';
                    specialProjectName.value = name;
                    @this.set('name', name);
                    const approvers = event.detail.approvers;
                    const selectApproverContainer = document.querySelector('#selectApproverContainer');

                    levelOfApprovalSelect.addEventListener('change', () => {
                        const levelOfApproval = parseInt(levelOfApprovalSelect.value);
                        selectApproverContainer.innerHTML = '';

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
                                    @this.set(`level${count}Approvers`, levelApprover.value);
                                });
                            }
                        }
                    });
                });

            } else {
                window.addEventListener('hide-special-project-container', () => {
                    @this.set('name', null);
                    specialProjectName.value = null;
                    levelOfApprovalSelect.reset();
                    specialProjectContainer.style.display = 'none';
                });
            }
        });

        window.addEventListener('checkAndShowContainer', () => {
            specialProjectCheck.checked = true;
            specialProjectContainer.style.display = 'block';
        });

        window.addEventListener('checkAndHideContainer', () => {
            specialProjectCheck.checked = false;
            specialProjectContainer.style.display = 'none';
        });
    }
</script>
@endpush

@push('livewire-select')
<script>
    const serviceLevelAgreementOption = [
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
        options: serviceLevelAgreementOption,
        search: true,
        markSearchResults: true,
    });
    slaSelect.addEventListener('change', () => {
        @this.set('sla', parseInt(slaSelect.value));
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
    });

    const teamSelect = document.querySelector('#select-help-topic-team');
    VirtualSelect.init({
        ele: teamSelect,
        search: true,
        markSearchResults: true,
    });
    teamSelect.disable();

    serviceDepartmentSelect.addEventListener('change', () => {
        const serviceDepartmentId = serviceDepartmentSelect.value;
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

    teamSelect.addEventListener('change', () => {
        const teamId = parseInt(teamSelect.value);
        if (teamId) @this.set('team', );
    });

    serviceDepartmentSelect.addEventListener('reset', () => {
        @this.set('teams', []); // Clear teams count when service department is resetted.
    });

</script>

@endpush

@push('livewire-modal')
<script>
    window.addEventListener('close-modal', () => {
        serviceDepartmentSelect.reset();
        slaSelect.reset();
        levelOfApprovalSelect.reset();
        teamSelect.reset();
        teamSelect.disable();
        teamSelect.setOptions([]);

        @this.set('name', null);
        specialProjectContainer.style.display = 'none';
    });
</script>
@endpush