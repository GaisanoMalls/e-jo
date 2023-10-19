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
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label for="name" class="form-label form__field__label">Name</label>
                                            <input type="text" wire:model.defer="name" class="form-control form__field"
                                                id="name" placeholder="Enter help topic name">
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
                                                <div id="slaSelect" wire:ignore></div>
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
                                                <div id="serviceDepartmentSelect" wire:ignore></div>
                                            </div>
                                            @error('serviceDepartment')
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
                                                <div id="teamSelect" placeholder="Select (optional)" wire:ignore></div>
                                            </div>
                                            @error('team')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <small class="fw-bold my-3">Approvals</small>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label class="form-label form__field__label">
                                                Level of approval
                                            </label>
                                            <div>
                                                <div id="levelOfApprovalSelect" wire:ignore></div>
                                            </div>
                                            @error('levelOfApproval')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
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
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
<script>
    const serviceLevelAgreementOption = [
        @foreach ($serviceLevelAgreements as $sla)
        {
            label: "{{ $sla->time_unit }}",
            value: "{{ $sla->countdown_approach }}"
        },
        @endforeach
    ];

    const slaSelect = document.querySelector('#slaSelect');
    VirtualSelect.init({
        ele: slaSelect,
        options: serviceLevelAgreementOption,
        search: true,
        markSearchResults: true,
    });
    slaSelect.addEventListener('change', () => {
        @this.set('sla', slaSelect.value);
    })

    const serviceDepartmentOption = [
        @foreach ($serviceDepartments as $serviceDepartment)
        {
            label: "{{ $serviceDepartment->name }}",
            value: "{{ $serviceDepartment->id }}"
        },
        @endforeach
    ];

    const serviceDepartmentSelect = document.querySelector('#serviceDepartmentSelect');
    VirtualSelect.init({
        ele: serviceDepartmentSelect,
        options: serviceDepartmentOption,
        search: true,
        markSearchResults: true,
    });

    const teamSelect = document.querySelector('#teamSelect');
    VirtualSelect.init({
        ele: teamSelect,
        search: true,
        markSearchResults: true,
    });
    teamSelect.disable();

    serviceDepartmentSelect.addEventListener('change', () => {
        const serviceDepartmentId = serviceDepartmentSelect.value;
        @this.set('serviceDepartment', serviceDepartmentId);

        if (serviceDepartmentId) {
            teamSelect.enable();
            window.addEventListener('get-teams-from-selected-service-department', event => {
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
        }
    });

    teamSelect.addEventListener('change', () => {
        @this.set('team', teamSelect.value);
    });

    const levelOfApprovalOption = [
        @foreach ($levelOfApprovals as $approval)
        {
            label: "{{ $approval->description }}",
            value: "{{ $approval->value }}"
        },
        @endforeach
    ];

    const levelOfApprovalSelect = document.querySelector('#levelOfApprovalSelect');
    const selectApproverContainer = document.querySelector('#selectApproverContainer');

    VirtualSelect.init({
        ele: levelOfApprovalSelect,
        options: levelOfApprovalOption,
        search: true,
        markSearchResults: true,
    });

    levelOfApprovalSelect.addEventListener('change', () => {
        const levelOfApproval = parseInt(levelOfApprovalSelect.value);
        selectApproverContainer.innerHTML = '';


        if (levelOfApproval) {
            @this.set('levelOfApproval', levelOfApproval);
            for (let i = 1; i <= levelOfApproval; i++) {
                const html = `
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label form__field__label">
                                Level ${i} approver/s
                            </label>
                            <div>
                                <div wire:ignore id="level_${i}_approver" placeholder="Choose an approver"></div>
                            </div>
                        </div>
                    </div>`;

                selectApproverContainer.insertAdjacentHTML('beforeend', html);

                window.addEventListener('load-approvers', event => {
                    const levelOfApproverSelect = document.getElementById(`level_${i}_approver`);
                    const approvers = event.detail.approvers;
                    const approverOption = [];

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
                        ele: `#level_${i}_approver`,
                        options: approverOption,
                        showValueAsTags: true,
                        markSearchResults: true,
                        multiple: true
                    });

                    levelOfApproverSelect.addEventListener('change', () => {
                        @this.set('levelApprovers', levelOfApproverSelect.value);
                    });
                });
            }
        }
    });
</script>
@endpush