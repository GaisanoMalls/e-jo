<div>
    <div class="row justify-content-center help__topics__section">
        <div class="col-lg-12">
            <div class="card d-flex flex-column gap-2 help__topic__details__card">
                <div class="help__topic__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Add New Help Topic</h6>
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
                                <div wire:ignore.self class="col-md-6" id="help-topic-name-container">
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
                        @if (session()->has('approval_config_error'))
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ session('approval_config_error') }}
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
                                @error('approvalLevelSelected')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div wire:ignore class="row" id="dynamic-approval-container"></div>
                        <div class="my-2"
                            style="text-align: left; display: flex; justify-content: flex-start; gap: 10px;">
                            <button wire:click="saveConfiguration"
                                class="btn d-flex align-items-center justify-content-center rounded-3"
                                style="width: auto; height: 30px; background-color: #3B4053; color: white; font-size: 0.75rem;">
                                Add approval
                            </button>
                            <button onclick="handleCancelApprovalConfig()" type="button"
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
                                        <th style="font-size: 0.85rem; padding: 17px 21px;">Numbers of Approvers</th>
                                        <th class="text-center" style="font-size: 0.85rem; padding: 17px 21px;">Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configurations as $index => $config)
                                        <tr>
                                            <td class="td__content" style="font-size: 0.85rem;">{{ $index + 1 }}</td>
                                            <td class="td__content" style="font-size: 0.85rem;">
                                                {{ $config['bu_department_name'] }}</td>
                                            <td class="td__content" style="font-size: 0.85rem;">
                                                {{ $config['approvers_count'] }}</td>
                                            <td>
                                                <div
                                                    class="d-flex align-items-center justify-content-center pe-2 gap-1">
                                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                                        data-tooltip-font-size="11px" type="button"
                                                        class="btn action__button">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm action__button mt-0"
                                                        wire:click="removeConfiguration({{ $index }})">
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
                <div wire:ignore id="specialProjectAmountContainer">
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
                                            <div id="help-topic-costing-approver" wire:ignore></div>
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
                                    </div>
                                </div>
                                <div class="col-md-4" id="costing-approver-container">
                                    <div class="mb-3">
                                        <label class="finalCostingApprover form-label form__field__label">Final Cost
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
                <div style="text-align: left; display: flex; justify-content: flex-start; gap: 10px;">
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send"
                        style="background-color: #d32839; color: white;" wire:click="saveHelpTopic"
                        onclick="resetFormFields()">
                        <span wire:loading wire:target="saveHelpTopic" class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true"></span>
                        Add New
                    </button>
                    <a href="{{ route('staff.manage.help_topic.index') }}" type="button"
                        class="btn m-0 btn__modal__footer btn__cancel" onclick="resetFormFields()">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        const amountField = document.querySelector('#amount');
        const specialProjectCheck = document.querySelector('#specialProjectCheck');
        const slaSelect = document.querySelector('#select-help-topic-sla');
        const specialProjectAmountContainer = document.querySelector('#specialProjectAmountContainer');
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
            const slaId = parseInt(event.target.value);
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
            @this.set('teams', []);
            @this.set('name', null);
            teamSelect.disable();
            teamSelect.setOptions([]);
        });

        if (specialProjectCheck && specialProjectAmountContainer) {
            specialProjectAmountContainer.style.display = specialProjectCheck.checked ? 'block' : 'none';

            specialProjectCheck.addEventListener('change', () => {
                if (specialProjectCheck.checked) {
                    serviceDepartmentSelect.reset();
                    teamSelect.disable();
                    specialProjectAmountContainer.style.display = 'block';
                } else {
                    teamSelect.enable();
                    specialProjectAmountContainer.style.display = 'none';
                }
            });
        }



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

        approvalLevelSelect.addEventListener('change', () => {
            @this.set('approvalLevelSelected', true);
        });

        approvalLevelSelect.addEventListener('reset', () => {
            @this.set('approvalLevelSelected', false);
        });

        const dynamicApprovalLevelContainer = document.querySelector('#dynamic-approval-container');
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

                window.dispatchEvent(new CustomEvent('approver-level-changed', {
                    detail: {
                        level
                    }
                }));
                console.log(`Level ${level} Approvers:`, approvers[`level${level}`].value);
            });

            approvers[`level${level}`].addEventListener('virtual-select:option-click', () => {
                @this.call('getFilteredApprovers', level);
            });
        };

        approvalLevelSelect.addEventListener('change', () => {
            dynamicApprovalLevelContainer.innerHTML = '';
            selectedApprovers = [];

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
            }
            window.dispatchEvent(new CustomEvent('approval-level-selected'));
        });

        window.addEventListener('load-approvers', (event) => {
            const level = event.detail.level;
            const approverSelect = approvers[`level${level}`];

            if (approverSelect) {
                const approverOptions = event.detail.approvers.filter(approver => {
                    return !selectedApprovers.flat().includes(approver.id);
                }).map(approver => ({
                    label: `${approver.profile.first_name} ${approver.profile.middle_name ? approver.profile.middle_name[0] + '.' : ''} ${approver.profile.last_name}`,
                    value: approver.id,
                    description: approver.roles.map(role => role.name).join(', ')
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

        // Costing Approver
        const costingApproverContainer = document.querySelector('#select-help-topic-costing-approver');
        const selectCostingApprover = @json($costingApproversList);

        VirtualSelect.init({
            ele: '#help-topic-costing-approver',
            options: selectCostingApprover,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
            hasOptionDescription: true,
        });

        document.querySelector('#help-topic-costing-approver').addEventListener('change', (event) => {
            const selectedCostingApprovers = event.target.value;
            console.log('Selected Costing Approvers:', selectedCostingApprovers);
            @this.set('costingApprovers', selectedCostingApprovers);
        });

        // Final Cost Approver
        const finalCostingApproverContainer = document.querySelector('#select-help-topic-final-costing-approver');
        const selectFinalCostingApprover = @json($finalCostingApproversList);

        VirtualSelect.init({
            ele: '#select-help-topic-final-costing-approver',
            options: selectFinalCostingApprover,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
            hasOptionDescription: true,
        });

        document.querySelector('#select-help-topic-final-costing-approver').addEventListener('change', (event) => {
            const selectedFinalCostingApprovers = event.target.value;
            console.log('Selected Final Costing Approvers:', selectedFinalCostingApprovers);
            @this.set('finalCostingApprovers', selectedFinalCostingApprovers);
        });


        function handleCancelApprovalConfig() {
            buDepartmentSelect.reset();
            approvalLevelSelect.reset();
            dynamicApprovalLevelContainer.innerHTML = '';
        }

        function resetFormFields() {
            document.querySelector('#helpTopicName').value = '';
            document.querySelector('#amount').value = '';
            document.querySelector('#specialProjectCheck').checked = false;

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

            document.querySelector('#dynamic-approval-container').innerHTML = '';
            specialProjectAmountContainer.style.display = 'none';
            teamSelect.disable();
        }
    </script>
@endpush
