<div>
    <div class="row justify-content-center help__topics__section">
        <div class="col-lg-12">
            <div class="card d-flex flex-column gap-2 help__topic__details__card">
                <div class="help__topic__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Create Help Topic</h6>
                </div>
                <!-- Form for saveHelpTopic -->
                <div class="row gap-4 help__topic__details__container">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="row">
                                <!-- Special Project Checkbox -->
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
                                <!-- Name Field -->
                                <div class="col-md-6" id="help-topic-name-container">
                                    <div class="mb-2">
                                        <label for="helpTopicName" class="form-label form__field__label">Name</label>
                                        <input type="text" wire:model.defer="name" class="form-control form__field"
                                            id="helpTopicName" placeholder="Enter help topic name">
                                        @error('name')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- SLA Field -->
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="sla" class="form-label form__field__label">Service Level
                                            Agreement (SLA)</label>
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
                                <!-- Service Department Field -->
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="department" class="form-label form__field__label">Service
                                            Department</label>
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
                                <!-- Team Field -->
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="team" class="form-label form__field__label">Team
                                            <span class="fw-normal" style="font-size: 13px;">
                                                @if ($teams && $teams->count() !== 0)
                                                    ({{ $teams->count() }})
                                                @endif
                                            </span>
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
                            </div>
                        </div>
                    </div>
                    <!-- Form fields -->
                    <hr>
                    <div class="row">
                        <h6 class="mb-0 fw-bold">Configurations</h6>
                    </div>
                    <div class="row mb-3">
                        <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"
                            style="font-size: 0.89rem; color: #9da85c;">
                            <i class="bi bi-caret-right-fill" style="font-size: 1rem;"></i>
                            Approval
                        </h6>
                        @if (session()->has('level_approver_message'))
                            <span class="text-danger mb-3" style="font-size: 0.9rem;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ session('level_approver_message') }}
                            </span>
                        @endif
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="department" class="form-label form__field__label">BU Department</label>
                                <div>
                                    <div id="select-help-topic-bu-department" wire:ignore></div>
                                </div>
                                @error('selectedBuDepartment')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="department" class="form-label form__field__label">Level of Approval</label>
                                <div>
                                    <div id="select-help-topic-approval-level" wire:ignore></div>
                                </div>
                                @error('selectedApprovalLevel')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div wire:ignore class="row" id="help-topic-approval-container"></div>
                        <div class="my-2"
                            style="text-align: left; display: flex; justify-content: flex-start; gap: 10px;">
                            <button wire:click="saveConfiguration"
                                class="btn d-flex align-items-center justify-content-center rounded-3"
                                style="height: 30px; background-color: #3B4053; color: white; font-size: 0.75rem;">
                                Add approval
                            </button>
                            <button wire:click="cancelConfiguration" type="button"
                                class="btn d-flex align-items-center justify-content-center rounded-3"
                                style="font-size: 0.75rem; height: 30px; color: #3e3d3d; background-color: #f3f4f6;">
                                Cancel
                            </button>
                        </div>
                        @if (!empty($configurations))
                            <table class="table mt-3" style="margin-left: 11px; margin-right: 11px;">
                                <thead>
                                    <tr>
                                        <th style="font-size: 0.85rem; padding: 17px 21px;">No.</th>
                                        <th style="font-size: 0.85rem; padding: 17px 21px;">BU Department</th>
                                        <th style="font-size: 0.85rem; padding: 17px 21px;">Approvers</th>
                                        <th class="text-center" style="font-size: 0.85rem; padding: 17px 21px;">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configurations as $index => $config)
                                        <tr>
                                            <td class="td__content" style="font-size: 0.85rem;">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="td__content" style="font-size: 0.85rem;">
                                                {{ $config['bu_department_name'] }}
                                            </td>
                                            <td class="td__content" style="font-size: 0.85rem;">
                                                {{ $config['approvers_count'] }}
                                            </td>
                                            <td>
                                                <div
                                                    class="d-flex align-items-center justify-content-center pe-2 gap-1">
                                                    <button wire:click="editConfiguration({{ $index }})"
                                                        type="button" class="btn action__button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editConfigurationModal">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button wire:click="removeConfiguration({{ $index }})"
                                                        type="button" class="btn action__button">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- Special Project Amount -->
                @if ($isSpecialProject)
                    <div class="row">
                        <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"
                            style="font-size: 0.89rem; color: #196837;">
                            <i class="bi bi-caret-right-fill" style="font-size: 1rem;"></i>
                            Costing
                        </h6>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="costingApprover" class="form-label form__field__label">Costing
                                                Approver</label>
                                            <div>
                                                <div id="select-help-topic-costing-approver" wire:ignore></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label form__field__label">Enter Maximum
                                                Total Cost</label>
                                            <div class="d-flex position-relative amount__field__container">
                                                <span class="currency text-muted position-absolute mt-2">₱</span>
                                                <input type="text" wire:model="amount"
                                                    class="form-control form__field amount__field" id="amount"
                                                    placeholder="Enter Total Cost">
                                            </div>
                                            @error('amount')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="costing-approver-container">
                                        <div class="mb-3">
                                            <label class="finalCostingApprover form-label form__field__label">Final
                                                Cost
                                                Approver</label>
                                            <div>
                                                <div id="select-help-topic-final-costing-approver" wire:ignore></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div style="text-align: left; display: flex; justify-content: flex-start; gap: 10px;">
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send"
                        style="background-color: #d32839; color: white;" wire:click="saveHelpTopic">
                        <span wire:loading wire:target="saveHelpTopic" class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true"></span>
                        Add New
                    </button>
                    <a href="{{ route('staff.manage.help_topic.index') }}" type="button"
                        class="btn m-0 btn__modal__footer btn__cancel">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
        {{-- Edit Configuration --}}
        <div wire:ignore.self class="modal fade help__topic__modal" id="editConfigurationModal" tabindex="-1"
            aria-labelledby="editConfigurationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content modal__content">
                    <div class="modal-header modal__header p-0 border-0">
                        <h1 class="modal-title modal__title" id="addNewTeamModalLabel">Edit Configuration</h1>
                        <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                            <i class="fa-sharp fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form wire:submit.prevent="saveEditConfiguration">
                        <div class="modal-body modal__body">
                            <div class="row mb-2">
                                @if (session()->has('edit_level_approver_message'))
                                    <span class="text-danger mb-3" style="font-size: 0.9rem;">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ session('edit_level_approver_message') }}
                                    </span>
                                @endif
                                <div class="col-md-6 mb-2">
                                    <label class="form-label form__field__label">
                                        BU Department
                                    </label>
                                    <div>
                                        <div id="select-edit-config-bu-department" wire:ignore></div>
                                    </div>
                                    @error('editBuDepartment')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label form__field__label">
                                        Level of Approval
                                    </label>
                                    <div>
                                        <div id="select-edit-config-level-of-approval" wire:ignore></div>
                                    </div>
                                    @error('editLevelOfApproval')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div wire:ignore class="row" id="edit-help-topic-approval-config-container"></div>
                            </div>
                        </div>
                        <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <button type="submit"
                                    class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                    <span wire:loading wire:target="saveEditConfiguration"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Update
                                </button>
                                <button type="button" class="btn m-0 btn__modal__footer btn__cancel" wire:click=""
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('livewire-select')
    <script>
        const amountField = document.querySelector('#amount');
        const slaSelect = document.querySelector('#select-help-topic-sla');
        const serviceDepartmentSelect = document.querySelector('#select-help-topic-service-department');

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

        slaSelect.addEventListener('change', (event) => {
            @this.set('sla', parseInt(event.target.value));
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

        const teamSelect = document.querySelector('#select-help-topic-team');
        VirtualSelect.init({
            ele: teamSelect,
            search: true,
            markSearchResults: true,
        });

        teamSelect.disable();

        serviceDepartmentSelect.addEventListener('change', (event) => {
            const serviceDepartmentId = event.target.value;

            if (serviceDepartmentId) {
                @this.set('serviceDepartment', serviceDepartmentId);
                teamSelect.enable();

                window.addEventListener('get-teams-from-selected-service-department', (event) => {
                    const teams = event.detail.teams;
                    const teamOption = [];

                    if (teams.length > 0) {
                        teams.forEach(function(team) {
                            teamOption.push({
                                label: team.name,
                                value: team.id
                            });
                        });

                        teamSelect.setOptions(teamOption);
                    } else {
                        teamSelect.disable();
                        teamSelect.setOptions([]);
                    }
                });

            } else {
                teamSelect.reset();
                teamSelect.disable();
                teamSelect.setOptions([]);
            }
        });

        teamSelect.addEventListener('change', (event) => {
            const teamId = parseInt(event.target.value);
            @this.set('team', teamId);
        });

        serviceDepartmentSelect.addEventListener('reset', () => {
            @this.set('teams', null);
            @this.set('name', null);
            teamSelect.disable();
            teamSelect.setOptions([]);
        });

        // Approval Configurations
        const buDepartmentSelect = document.querySelector('#select-help-topic-bu-department');
        const approvalLevelSelect = document.querySelector('#select-help-topic-approval-level');

        const buDepartments = @json($buDepartments);
        const buDepartmentOption = buDepartments.map(buDepartment => ({
            label: buDepartment.name,
            value: buDepartment.id
        }));

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

        buDepartmentSelect.addEventListener('change', (event) => {
            @this.set('selectedBuDepartment', parseInt(event.target.value));
        });

        const dynamicApprovalLevelContainer = document.querySelector('#help-topic-approval-container');
        const approvers = {};
        let selectedApprovers = [];

        const initializeApproverSelect = (level) => {
            approvers[`level${level}`] = document.querySelector(`#select-help-topic-approval-level-${level}`);

            VirtualSelect.init({
                ele: approvers[`level${level}`],
                search: true,
                multiple: true,
                showValueAsTags: true,
                markSearchResults: true,
                hasOptionDescription: true
            });

            approvers[`level${level}`].addEventListener('change', () => {
                selectedApprovers[level - 1] = approvers[`level${level}`].value;
                @this.set(`level${level}Approvers`, approvers[`level${level}`].value);
            });

            approvers[`level${level}`].addEventListener('virtual-select:option-click', () => {
                @this.call('getFilteredApprovers', level);
            });
        };

        approvalLevelSelect.addEventListener('reset', () => {
            @this.set('selectedApprovalLevel', false);
        });

        approvalLevelSelect.addEventListener('change', () => {
            @this.set('selectedApprovalLevel', true);
            @this.set('levelOfApproval', parseInt(event.target.value));

            dynamicApprovalLevelContainer.innerHTML = '';
            selectedApprovers = [];
            const selectedLevels = [];

            for (let i = 1; i <= approvalLevelSelect.value; i++) {
                const approverFieldWrapper = document.createElement('div');
                approverFieldWrapper.className = 'col-md-6';
                approverFieldWrapper.innerHTML = `
                    <div class="mb-2">
                        <label for="department" class="form-label form__field__label">Level ${i} Approver</label>
                        <div>
                            <div id="select-help-topic-approval-level-${i}" wire:ignore></div>
                        </div>
                    </div>`;

                dynamicApprovalLevelContainer.appendChild(approverFieldWrapper);
                initializeApproverSelect(i);

                selectedLevels.push(i);
                @this.set('selectedLevels', selectedLevels);
            }
        });

        window.addEventListener('load-approvers', (event) => {
            const level = event.detail.level;
            const levelApprovers = event.detail.approvers;
            const approverSelect = approvers[`level${level}`];

            if (approverSelect && levelApprovers.length > 0) {
                const approverOptions = levelApprovers.map(approver => ({
                    label: `${approver.profile.first_name} ${approver.profile.middle_name ? approver.profile.middle_name[0] + '.' : ''} ${approver.profile.last_name}`,
                    value: approver.id,
                    description: `${approver.roles.map(role => role.name).join(', ')} (${approver.bu_departments.map(department => department.name).join(', ')})`
                }));

                approverSelect.setOptions(approverOptions);
            }
        });

        //reset fields after save config
        window.addEventListener('reset-select-fields', () => {
            buDepartmentSelect.reset();
            approvalLevelSelect.reset();
            dynamicApprovalLevelContainer.innerHTML = '';
        });

        window.addEventListener('show-costing-section', () => {
            // Costing Approver
            const selectHelpTopicCostingApprover = document.querySelector('#select-help-topic-costing-approver');
            const selectCostingApprover = @json($costingApproversList);

            VirtualSelect.init({
                ele: selectHelpTopicCostingApprover,
                options: selectCostingApprover,
                search: true,
                multiple: true,
                showValueAsTags: true,
                markSearchResults: true,
                hasOptionDescription: true,
            });

            selectHelpTopicCostingApprover.addEventListener('change', (event) => {
                const selectedCostingApprovers = event.target.value;
                @this.set('costingApprovers', selectedCostingApprovers);
            });

            // Final Cost Approver
            const selectHelpTopicFinalCosting = document.querySelector('#select-help-topic-final-costing-approver');
            const selectFinalCostingApprover = @json($finalCostingApproversList);

            VirtualSelect.init({
                ele: selectHelpTopicFinalCosting,
                options: selectFinalCostingApprover,
                search: true,
                multiple: true,
                showValueAsTags: true,
                markSearchResults: true,
                hasOptionDescription: true,
            });

            selectHelpTopicFinalCosting.addEventListener('change', (event) => {
                const selectedFinalCostingApprovers = event.target.value;
                @this.set('finalCostingApprovers', selectedFinalCostingApprovers);
            });
        });

        window.addEventListener('reset-help-topic-form-fields', () => {
            const selectElements = [
                '#select-help-topic-sla',
                '#select-help-topic-service-department',
                '#select-help-topic-team',
                '#select-help-topic-bu-department',
                '#select-help-topic-approval-level',
                '#select-help-topic-costing-approver',
                '#select-help-topic-final-costing-approver'
            ];

            selectElements.forEach(selector => {
                const selectElement = document.querySelector(selector);
                if (selectElement && selectElement.virtualSelect) {
                    selectElement.virtualSelect.reset();
                }
            });

            document.querySelector('#help-topic-approval-container').innerHTML = '';
            teamSelect.disable();
        });

        // Edit Configuration
        const selectEditBuDepartment = document.querySelector('#select-edit-config-bu-department');
        const selectEditConfigLevelOfApproval = document.querySelector('#select-edit-config-level-of-approval');

        VirtualSelect.init({
            ele: selectEditBuDepartment,
            options: buDepartmentOption,
        });

        VirtualSelect.init({
            ele: selectEditConfigLevelOfApproval,
            options: approvalLevelOption,
        });

        window.addEventListener('edit-help-topic-configuration', (event) => {
            const buDeptId = event.detail.editBuDepartment;
            const levelOfApproval = event.detail.editLevelOfApproval;

            selectEditBuDepartment.reset();
            selectEditConfigLevelOfApproval.reset();

            selectEditBuDepartment.setValue(buDeptId);
            selectEditConfigLevelOfApproval.setValue(levelOfApproval);
        });

        selectEditBuDepartment.addEventListener('change', (event) => {
            @this.set('editBuDepartment', parseInt(event.target.value));
        });

        selectEditBuDepartment.addEventListener('reset', () => {
            @this.set('editBuDepartment', null);
        });

        const editHelpTopicApprovalConfigContainer = document.querySelector('#edit-help-topic-approval-config-container');
        let editSelectedApprovers = [];
        let editApprovers = {};

        selectEditConfigLevelOfApproval.addEventListener('change', () => {
            @this.set('editLevelOfApproval', parseInt(event.target.value));

            editHelpTopicApprovalConfigContainer.innerHTML = '';
            editSelectedApprovers = [];
            const editSelectedLevels = [];

            for (let level = 1; level <= selectEditConfigLevelOfApproval.value; level++) {
                const approverFieldWrapper = document.createElement('div');
                approverFieldWrapper.className = 'col-md-6';
                approverFieldWrapper.innerHTML = `
                    <div class="mb-2">
                        <label for="department" class="form-label form__field__label">Level ${level} Approver</label>
                        <div>
                            <div id="edit-select-help-topic-approval-level-${level}" wire:ignore></div>
                        </div>
                    </div>`;

                editHelpTopicApprovalConfigContainer.appendChild(approverFieldWrapper);

                editApprovers[`level${level}`] = document.querySelector(
                    `#edit-select-help-topic-approval-level-${level}`);

                VirtualSelect.init({
                    ele: editApprovers[`level${level}`],
                    search: true,
                    multiple: true,
                    showValueAsTags: true,
                    markSearchResults: true,
                    hasOptionDescription: true
                });

                editApprovers[`level${level}`].addEventListener('change', () => {
                    editSelectedApprovers[level - 1] = editApprovers[`level${level}`].value;
                    @this.set(`editLevel${level}Approvers`, editApprovers[`level${level}`].value);
                });

                editApprovers[`level${level}`].addEventListener('virtual-select:option-click', () => {
                    @this.call('getFilteredApprovers', level);
                });

                editSelectedLevels.push(level);
                @this.set('editSelectedLevels', editSelectedLevels);
            }
        });

        selectEditConfigLevelOfApproval.addEventListener('reset', () => {
            @this.set('editLevelOfApproval', null);
        })

        window.addEventListener('edit-load-approvers', (event) => {
            const levelApprovers = Object.values(event.detail.currentEditLevelApprovers);
            const approvers = event.detail.approvers;
            const level = event.detail.level;
            const editApproverSelect = editApprovers[`level${level}`];

            if (editApproverSelect) {
                const approverOptions = approvers.map(approver => ({
                    label: `${approver.profile.first_name} ${approver.profile.middle_name ? approver.profile.middle_name[0] + '.' : ''} ${approver.profile.last_name}`,
                    value: approver.id,
                    description: `${approver.roles.map(role => role.name).join(', ')} (${approver.bu_departments.map(department => department.name).join(', ')})`
                }));

                editApproverSelect.setOptions(approverOptions);

                if (Array.isArray(levelApprovers)) {
                    const approverKey = `level${level}`;
                    const assignedApprover = levelApprovers
                        .find(lvl => lvl.approvers && lvl.approvers[approverKey]);

                    if (assignedApprover) {
                        const approverValue = assignedApprover.approvers[approverKey];
                        editApproverSelect.setValue(approverValue);
                    }
                }
            }
        });

        window.addEventListener('edit-reset-select-fields', () => {
            selectEditBuDepartment.reset();
            selectEditConfigLevelOfApproval.reset();
            editHelpTopicApprovalConfigContainer.innerHTML = '';
            $('#editConfigurationModal').modal('hide');
        });
    </script>
@endpush
