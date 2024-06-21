<div>
    <div wire:ignore.self class="modal fade help__topic__modal" id="addNewHelpTopicModal" tabindex="-1"
        aria-labelledby="addNewHelpTopicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
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
                                            <input wire:model="isSpecialProject"
                                                class="form-check-input check__special__project" type="checkbox"
                                                role="switch" id="specialProjectCheck" wire:loading.attr="disabled">
                                            <label class="form-check-label" for="specialProjectCheck">
                                                Check if the help topic is a special project
                                            </label>
                                        </div>
                                    </div>
                                    <div wire:ignore.self class="col-md-6" id="help-topic-name-container">
                                        <div class="mb-2">
                                            <label for="helpTopicName"
                                                class="form-label form__field__label">Name</label>
                                            <input type="text" wire:model.defer="name"
                                                class="form-control form__field" id="helpTopicName"
                                                placeholder="Enter help topic name">
                                            @error('name')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="sla" class="form-label form__field__label">
                                                Service Level Agreement (SLA)
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
                                            @error('serviceDepartment')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div wire:ignore.self class="col-md-6" id="serviceDeptChildContainer">
                                        <div class="mb-2">
                                            <label for="department" class="form-label form__field__label">
                                                Sub-Service Department
                                            </label>
                                            <div>
                                                <div id="select-help-topic-service-department-children" wire:ignore>
                                                </div>
                                            </div>
                                            @if (session()->has('sub_service_department_error'))
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ session('sub_service_department_error') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="teamSelectContainer" wire:ignore>
                                        <div class="mb-2">
                                            <label for="team" class="form-label form__field__label">
                                                Team
                                                <span class="fw-normal" style="font-size: 13px;" id="countTeams"></span>
                                            </label>
                                            <div>
                                                <div id="select-help-topic-team" placeholder="Select (optional)"
                                                    wire:ignore>
                                                </div>
                                            </div>
                                            @error('team')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div wire:ignore class="mt-2" id="specialProjectAmountContainer">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label form__field__label">
                                                        Amount
                                                    </label>
                                                    <div class="d-flex position-relative amount__field__container">
                                                        <span class="currency text-muted position-absolute">₱</span>
                                                        <input type="text" wire:model="amount"
                                                            class="form-control form__field amount__field"
                                                            id="amount" placeholder="Enter amount">
                                                    </div>
                                                </div>
                                            </div>
                                            <div wire:ignore.self class="col-md-6" id="costing-approver-container">
                                                <div class="mb-3">
                                                    <label class="form-label form__field__label">
                                                        Cost Approver
                                                    </label>
                                                    <div>
                                                        <div id="select-help-topic-costing-approver" wire:ignore></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <h6 class="fw-semibold mb-4" style="font-size: 0.89rem;">Approval Configurations</h6>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="department" class="form-label form__field__label">
                                        BU Department
                                    </label>
                                    <div>
                                        <div id="select-help-topic-bu-department" wire:ignore></div>
                                    </div>
                                    @error('bu_department')
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
                                        Level of Approval
                                    </label>
                                    <div>
                                        <div id="select-help-topic-approval-level" wire:ignore></div>
                                    </div>
                                    @error('bu_department')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div wire:ignore class="row" id="dynamic-approval-container"></div>
                        </div>
                        <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <button type="submit"
                                    class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                                    <span wire:loading wire:target="saveHelpTopic"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Add New
                                </button>
                                <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                                    id="btnCloseModal" data-bs-dismiss="modal" wire:click="cancel">
                                    Cancel
                                </button>
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
        const amountField = document.querySelector('#amount');
        const teamSelectContainer = document.querySelector('#teamSelectContainer');
        const specialProjectCheck = document.querySelector('#specialProjectCheck');
        const specialProjectAmountContainer = document.querySelector('#specialProjectAmountContainer');
        const serviceDeptChildContainer = document.querySelector('#serviceDeptChildContainer');
        const slaSelect = document.querySelector('#select-help-topic-sla');
        const serviceDepartmentSelect = document.querySelector('#select-help-topic-service-department');
        const serviceDepartmentChildrenSelect = document.querySelector('#select-help-topic-service-department-children');

        const serviceLevelAgreementOption = @json($serviceLevelAgreements).map(sla => ({
            label: sla.time_unit,
            value: sla.id
        }))

        VirtualSelect.init({
            ele: slaSelect,
            options: serviceLevelAgreementOption,
            search: true,
            markSearchResults: true,
        });

        slaSelect.addEventListener('change', () => {
            const slaId = parseInt(slaSelect.value);
            @this.set('sla', slaId);
        });

        const serviceDepartmentOption = @json($serviceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }));

        VirtualSelect.init({
            ele: serviceDepartmentSelect,
            options: serviceDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        VirtualSelect.init({
            ele: serviceDepartmentChildrenSelect,
            search: true,
            markSearchResults: true,
        });

        const teamSelect = document.querySelector('#select-help-topic-team');
        VirtualSelect.init({
            ele: teamSelect,
            search: true,
            markSearchResults: true,
        });

        serviceDepartmentChildrenSelect.disable();
        teamSelect.disable();

        serviceDepartmentSelect.addEventListener('change', () => {
            const serviceDepartmentId = serviceDepartmentSelect.value;

            if (serviceDepartmentId) {
                @this.set('serviceDepartment', serviceDepartmentId);

                if (!specialProjectCheck.checked) {
                    teamSelect.enable();
                }

                window.addEventListener('get-teams-from-selected-service-department', (event) => {
                    const teams = event.detail.teams;
                    const teamOption = [];

                    if (teams.length > 0) {
                        teams.forEach(function(team) {
                            VirtualSelect.init({
                                ele: teamSelect,
                            });

                            teamOption.push({
                                label: team.name,
                                value: team.id
                            });
                        });

                        teamSelect.setOptions(teamOption);

                        const countTeams = document.querySelector('#countTeams');
                        countTeams.textContent = `(${event.detail.teams.length})`;

                    } else {
                        teamSelect.disable();
                        teamSelect.setOptions([]);
                    }
                });

                window.addEventListener('get-service-department-children', (event) => {
                    const serviceDepartmentChildren = event.detail.serviceDepartmentChildren;
                    const serviceDepartmentChildrenOption = [];
                    console.log(serviceDepartmentChildren);

                    if (serviceDepartmentChildren.length > 0) {
                        serviceDepartmentChildrenSelect.enable();

                        serviceDepartmentChildren.forEach((child) => {
                            serviceDepartmentChildrenOption.push({
                                label: child.name,
                                value: child.id
                            });
                        });

                        serviceDepartmentChildrenSelect.setOptions(serviceDepartmentChildrenOption);
                    } else {
                        serviceDepartmentChildrenSelect.disable();
                        serviceDepartmentChildrenSelect.setOptions();
                    }
                });

            } else {
                teamSelect.reset();
                teamSelect.disable();
                teamSelect.setOptions([]);
            }
        });

        teamSelect.addEventListener('change', () => {
            const teamId = parseInt(teamSelect.value);
            if (teamId) @this.set('team', teamId);
        });

        serviceDepartmentSelect.addEventListener('reset', () => {
            const countTeams = document.querySelector('#countTeams');

            @this.set('teams', []); // Clear teams count when service department is resetted.
            @this.set('name', null);

            countTeams.textContent = '';
            serviceDepartmentChildrenSelect.disable()
            serviceDepartmentChildrenSelect.reset();
            serviceDepartmentChildrenSelect.setOptions([]);
        });

        if (specialProjectCheck && specialProjectAmountContainer || serviceDeptChildContainer) {
            specialProjectAmountContainer.style.display = specialProjectCheck.checked ? 'block' : 'none';
            serviceDeptChildContainer.style.display = specialProjectCheck.checked ? 'block' : 'none';

            specialProjectCheck.addEventListener('change', () => {
                helpTopicNameContainer = document.querySelector('#help-topic-name-container');
                if (specialProjectCheck.checked) {
                    serviceDepartmentSelect.reset();
                }

                window.addEventListener('show-special-project-container', (event) => {
                    @this.set('team', null);
                    amountField.required = true;
                    teamSelect.disable();
                    teamSelectContainer.style.display = 'none';
                    helpTopicNameContainer.style.display = 'none';
                    specialProjectAmountContainer.style.display = 'block';
                    serviceDeptChildContainer.style.display = 'block'

                    serviceDepartmentSelect.addEventListener('change', () => {
                        const serviceDepartmentId = serviceDepartmentSelect.value;
                        const serviceDepartments = @json($serviceDepartments);

                        window.addEventListener('get-service-department-children', (event) => {
                            const serviceDepartmentChildren = event.detail
                                .serviceDepartmentChildren;
                            const serviceDepartmentChildrenOption = [];

                            serviceDepartmentChildren.forEach((child) => {
                                serviceDepartmentChildrenOption.push({
                                    label: child.name,
                                    value: child.id
                                });
                            });

                            serviceDepartmentChildrenSelect.setOptions(
                                serviceDepartmentChildrenOption);

                            serviceDepartmentChildrenSelect.addEventListener('change',
                                () => {
                                    serviceDepartmentChildrenId = parseInt(
                                        serviceDepartmentChildrenSelect.value);
                                    serviceDepartmentChildName =
                                        serviceDepartmentChildrenSelect
                                        .getDisplayValue();

                                    if (serviceDepartmentChildrenId) {
                                        @this.set('selectedServiceDepartmentChildrenId',
                                            serviceDepartmentChildrenId);
                                        @this.set(
                                            'selectedServiceDepartmentChildrenName',
                                            serviceDepartmentChildName
                                        )
                                    }
                                });
                        });

                        serviceDepartments.forEach((department) => {
                            if (serviceDepartmentId == department.id) {
                                if (specialProjectCheck.checked) {
                                    @this.set('name', `(SP) ${department.name}`);
                                }
                            }
                        });
                    });
                });

                window.addEventListener('hide-special-project-container', () => {
                    teamSelect.enable();
                    amountField.required = false;
                    teamSelectContainer.style.display = 'block';
                    helpTopicNameContainer.style.display = 'block';
                    specialProjectAmountContainer.style.display = 'none';
                    serviceDeptChildContainer.style.display = 'none';
                    serviceDepartmentSelect.reset();
                });
            });
        }

        // Costing approver
        const costingApproverContainer = document.querySelector('#costing-approver-container');
        const selectHelpTopicCostingApprover = document.querySelector('#select-help-topic-costing-approver');

        VirtualSelect.init({
            ele: selectHelpTopicCostingApprover,
        });

        costingApproverContainer.style.display = 'none';

        window.addEventListener('show-select-costing-approver', (event) => {
            costingApproverContainer.style.display = 'block';
        });

        window.addEventListener('hide-select-costing-approver', (event) => {
            selectHelpTopicCostingApprover.reset();
            costingApproverContainer.style.display = 'none';
        });

        window.addEventListener('hide-special-project-container', () => {
            selectHelpTopicCostingApprover.reset();
        });

        selectHelpTopicCostingApprover.addEventListener('change', () => {
            @this.set('costingApprovers', selectHelpTopicCostingApprover.value);
        });

        // Approval Configurations
        const buDepartmentSelect = document.querySelector('#select-help-topic-bu-department');
        const approvalLevelSelect = document.querySelector('#select-help-topic-approval-level');

        const buDepartments = @json($buDepartments);
        const buDepartmentOption = buDepartments.map(buDepartment => ({
            label: buDepartment.name,
            value: buDepartment.id
        }))

        const approvalLevels = @json($approvalLevels);
        const approvalLevelOption = approvalLevels.map(approvalLevel => ({
            label: `${approvalLevel} ${approvalLevel >= 2 ? 'Levels' : 'Level'}`,
            value: approvalLevel
        }));

        VirtualSelect.init({
            ele: buDepartmentSelect,
            options: buDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        VirtualSelect.init({
            ele: approvalLevelSelect,
            options: approvalLevelOption,
            search: true,
            markSearchResults: true,
        });

        buDepartmentSelect.addEventListener('change', () => {
            @this.set('buDepartment', parseInt(buDepartmentSelect.value));
        });

        approvalLevelSelect.addEventListener('change', () => {
            @this.set('approvalLevelSelected', true);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const dynamicApprovalLevelContainer = document.querySelector('#dynamic-approval-container');

            approvalLevelSelect.addEventListener('change', () => {
                dynamicApprovalLevelContainer.innerHTML = '';
                const approver = {};

                for (i = 1; i <= approvalLevelSelect.value; i++) {
                    const approverFieldWrapper = document.createElement('div');
                    approverFieldWrapper.className = 'col-md-6';

                    approverFieldWrapper.innerHTML = `
                        <div class="mb-2">
                            <label for="department" class="form-label form__field__label">
                                Level ${i} Approver
                            </label>
                            <div>
                                <div id="select-help-topic-approval-level-${i}" wire:ignore></div>
                            </div>
                        </div>`;

                    dynamicApprovalLevelContainer.appendChild(approverFieldWrapper);
                    approver[`level${i}`] = document.querySelector(
                        `#select-help-topic-approval-level-${i}`
                    );

                    VirtualSelect.init({
                        ele: approver[`level${i}`],
                        search: true,
                        multiple: true,
                        showValueAsTags: true,
                        markSearchResults: true,
                        hasOptionDescription: true
                    });
                }

                window.addEventListener('load-initial-approvers', (event) => {
                    const levelApprovers = event.detail.levelApprovers;
                    const approverOption = [];

                    levelApprovers.forEach((approver) => {
                        approver.roles.forEach((role) => {
                            const middleName =
                                `${approver.profile.middle_name ?? ''}`;
                            const firstLetter = middleName.length > 0 ?
                                middleName[0] + '.' : '';

                            approverOption.push({
                                label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                                value: approver.id,
                                description: role.name
                            });
                        });
                    });

                    const level1ApproverSelect = approver['level1'];
                    const level2ApproverSelect = approver['level2'];
                    const level3ApproverSelect = approver['level3'];
                    const level4ApproverSelect = approver['level4'];
                    const level5ApproverSelect = approver['level5'];

                    if (level1ApproverSelect) {
                        level1ApproverSelect.setOptions(approverOption);
                        level1ApproverSelect.addEventListener('change', () => {
                            @this.set('level1Approvers', level1ApproverSelect.value);
                        });
                    }

                    if (level2ApproverSelect) {
                        level2ApproverSelect.setOptions(approverOption);
                    }

                    if (level3ApproverSelect) {
                        level3ApproverSelect.setOptions(approverOption);
                    }

                    if (level4ApproverSelect) {
                        level4ApproverSelect.setOptions(approverOption);
                    }

                    if (level5ApproverSelect) {
                        level5ApproverSelect.setOptions(approverOption);
                    }

                    // TODO: Level Approvers - Exlcude the added approvers from the updated approver list
                    // if (level1ApproverSelect) {
                    //     level1ApproverSelect.setOptions(approverOption);

                    //     level1ApproverSelect.addEventListener('change', () => {
                    //         @this.set('level1Approvers', level1ApproverSelect.value);

                    //         window.addEventListener('remaining-approvers-from-level1', (
                    //             event) => {
                    //             const updatedLevelApprovers = event.detail
                    //                 .levelApprovers;
                    //             const updatedApproverOption = [];

                    //             updatedLevelApprovers.forEach((approver) => {
                    //                 approver.roles.forEach((role) => {
                    //                     const middleName =
                    //                         `${approver.profile.middle_name ?? ''}`;
                    //                     const firstLetter =
                    //                         middleName.length >
                    //                         0 ?
                    //                         middleName[0] +
                    //                         '.' :
                    //                         '';

                    //                     updatedApproverOption
                    //                         .push({
                    //                             label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                    //                             value: approver
                    //                                 .id,
                    //                             description: role
                    //                                 .name
                    //                         });
                    //                 });
                    //             });
                    //         });
                    //     });
                    // }

                    // if (level2ApproverSelect) {
                    //     level2ApproverSelect.addEventListener('change', () => {
                    //         @this.set('level2Approvers', level2ApproverSelect.value);

                    //         window.addEventListener('remaining-approvers-from-level2', (
                    //             event) => {
                    //             const updatedLevelApprovers = event.detail
                    //                 .levelApprovers;
                    //             const updatedApproverOption = [];

                    //             updatedLevelApprovers.forEach((approver) => {
                    //                 approver.roles.forEach((role) => {
                    //                     const middleName =
                    //                         `${approver.profile.middle_name ?? ''}`;
                    //                     const firstLetter =
                    //                         middleName.length > 0 ?
                    //                         middleName[0] + '.' :
                    //                         '';

                    //                     updatedApproverOption.push({
                    //                         label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                    //                         value: approver
                    //                             .id,
                    //                         description: role
                    //                             .name
                    //                     });
                    //                 });
                    //             });
                    //         })
                    //     });
                    // }
                });
            });
        });
    </script>
@endpush

@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            @this.set('name', null);

            slaSelect.reset();
            serviceDepartmentSelect.reset();
            serviceDepartmentChildrenSelect.reset();
            serviceDepartmentChildrenSelect.setOptions([]);
            serviceDepartmentChildrenSelect.disable();

            teamSelect.reset();
            teamSelect.disable();
            teamSelect.setOptions([]);

            specialProjectAmountContainer.style.display = 'none';
            serviceDeptChildContainer.style.display = 'none';
        });
    </script>
@endpush
