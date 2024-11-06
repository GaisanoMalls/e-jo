<div>
    <div class="row justify-content-center help__topics__section">
        <div class="col-lg-12">
            <div class="card d-flex flex-column gap-2 help__topic__details__card">
                <div class="help__topic__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Update Help Topic</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated: {{ $helpTopic->dateUpdated() }}
                    </small>
                </div>
                <input type="hidden" value="{{ $helpTopic->id }}" id="helpTopicID">
                <div class="row gap-4 help__topic__details__container">
                    <div class="col-12">
                        <div class="row">
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
                                    <label for="serviceDepartment" class="form-label form__field__label">
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
                            <!-- Team Field -->
                            <div class="col-md-6" id="teamSelectContainer" wire:ignore>
                                <div class="mb-2">
                                    <label for="team" class="form-label form__field__label">Team <span
                                            class="fw-normal" style="font-size: 13px;" id="countTeams"></span></label>
                                    <div>
                                        <div id="select-help-topic-team" placeholder="Select (optional)" wire:ignore>
                                        </div>
                                    </div>
                                    @if (session()->has('team_error'))
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ session('team_error') }}
                                        </span>
                                    @endif
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
                                <label for="department" class="form-label form__field__label">Level of
                                    Approval
                                </label>
                                <div>
                                    <div id="select-help-topic-approval-level" wire:ignore></div>
                                </div>
                                @error('levelOfApproval')
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
                                style="height: 30px; background-color: #3B4053; color: white; font-size: 0.75rem;">
                                Add approval
                            </button>
                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center rounded-3"
                                style="font-size: 0.75rem; height: 30px; color: #3e3d3d; background-color: #f3f4f6;">
                                Cancel
                            </button>
                        </div>
                        @if (!empty($addedConfigurations))
                            <div class="d-flex flex-column mt-3">
                                <h6 class="mb-0" style="font-size: 0.88rem;">Added Configuration</h6>
                                <table class="table">
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
                                        @foreach ($addedConfigurations as $index => $addedConfig)
                                            <tr wire:key="config-{{ $index + 1 }}">
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $addedConfig['bu_department_name'] }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $addedConfig['approvers_count'] }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center pe-2 gap-1">
                                                        <button type="button" class="btn btn-sm action__button mt-0"
                                                            wire:click="deleteAddedConfig({{ $index }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if ($currentConfigurations->isNotEmpty())
                            <div class="d-flex flex-column mt-3">
                                <h6 class="mb-0" style="font-size: 0.88rem;">Current Configuration</h6>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 0.85rem; padding: 17px 21px;">No.</th>
                                            <th style="font-size: 0.85rem; padding: 17px 21px;">BU Department</th>
                                            <th style="font-size: 0.85rem; padding: 17px 21px;">Level of Approvals</th>
                                            <th style="font-size: 0.85rem; padding: 17px 21px;">Approvers</th>
                                            <th class="text-center" style="font-size: 0.85rem; padding: 17px 21px;">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($currentConfigurations as $index => $currentConfig)
                                            <tr wire:key="config-{{ $currentConfig->id }}">
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $currentConfig->buDepartment->name }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $currentConfig->level_of_approval }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    {{ $currentConfig->approvers()->count() }}
                                                </td>
                                                <td class="td__content" style="font-size: 0.85rem;">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center pe-2 gap-1">
                                                        <button type="button" class="btn btn-sm action__button"
                                                            wire:click="editCurrentConfiguration({{ $currentConfig->id }})"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editCurrentConfigurationModal">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm action__button mt-0"
                                                            wire:click="confirmDeleteCurrentConfiguration({{ $currentConfig->id }})"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteCurrentConfigurationModal">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Costing Configuration -->
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
                                                Approver
                                            </label>
                                            <div>
                                                <div id="select-help-topic-costing-approver" wire:ignore></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label form__field__label">Enter
                                                Maximum Total Cost
                                            </label>
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
                                            <label class="form-label form__field__label">
                                                Final Cost Approver
                                            </label>
                                            <div>
                                                <div id="select-help-topic-final-costing-approver" wire:ignore>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2">
                        <button wire:click="updateHelpTopic" type="button"
                            class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send"
                            style="background-color: #d32839; color: white;" wire:click="updateHelpTopic">
                            <span wire:loading wire:target="updateHelpTopic" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Update
                        </button>
                        <a href="{{ route('staff.manage.help_topic.index') }}" type="button"
                            class="btn m-0 btn__modal__footer btn__cancel">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- Edit configuration approvers --}}
        <div wire:ignore.self class="modal fade edit__help__topic__config__modal" id="editCurrentConfigurationModal"
            tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal__content">
                    <div class="modal-body border-0 p-4 d-flex flex-column gap-1">
                        <h6>Configuration</h6>
                        <div class="mb-2">
                            <label for="approvers" class="form-label form__field__label">
                                BU Department
                            </label>
                            <input class="form-control form__field" type="text"
                                value="{{ $currentConfigBuDepartment?->name }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="approvers" class="form-label form__field__label">Assigned Approvers</label>
                            <div>
                                <div id="select-edit-config-approvers" wire:ignore></div>
                            </div>
                            @error('selectedBuDepartment')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="modal-footer modal__footer p-0 justify-content-start border-0 gap-2 mt-2">
                            <button wire:click="updateCurrentConfiguration" type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send"
                                style="background-color: #d32839; color: white;">
                                <span wire:loading="updateCurrentConfiguration"
                                    wire:target="updateCurrentConfiguration" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Update
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" wire:click=""
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete help topic configuration --}}
        <div wire:ignore.self class="modal fade modal__confirm__delete__help__topic"
            id="confirmDeleteCurrentConfigurationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal__content">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h6 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h6>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Delete help topic configuration?
                        </p>
                        <strong>{{ $deleteSelectedConfigBuDeptName }}</strong>
                    </div>
                    <hr>
                    <div wire:click="cancelDeleteConfiguration"
                        class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal"
                            style="padding: 0.6rem 1rem;
                                    border-radius: 0.563rem;
                                    font-size: 0.875rem;
                                    border: 1px solid #e7e9eb;
                                    background-color: transparent;
                                    color: #d32839;
                                    font-weight: 500;">
                            Cancel
                        </button>
                        <button type="button"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                            wire:click="deleteConfiguration"
                            style="padding: 0.6rem 1rem;
                                    border-radius: 0.563rem;
                                    font-size: 0.875rem;
                                    background-color: #d32839;
                                    color: white;
                                    font-weight: 500;
                                    box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);">
                            <span wire:loading wire:target="deleteConfiguration"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('livewire-select')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountField = document.querySelector('#amount');
            const teamSelectContainer = document.querySelector('#teamSelectContainer');
            const specialProjectCheck = document.querySelector('#specialProjectCheck');
            const slaSelect = document.querySelector('#select-help-topic-sla');
            const specialProjectAmountContainer = document.querySelector('#specialProjectAmountContainer');
            const serviceDepartmentSelect = document.querySelector('#select-help-topic-service-department');

            const serviceLevelAgreementOption = @json($serviceLevelAgreements).map(sla => ({
                label: sla.time_unit,
                value: sla.id
            }));

            VirtualSelect.init({
                ele: slaSelect,
                options: serviceLevelAgreementOption,
                search: true,
                markSearchResults: true,
                selectedValue: '{{ $sla }}'
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
                selectedValue: '{{ $serviceDepartment }}'
            });

            const teamOption = @json($teams).map(tm => ({
                label: tm.name,
                value: tm.id
            }));

            const teamSelect = document.querySelector('#select-help-topic-team');
            VirtualSelect.init({
                ele: teamSelect,
                options: teamOption,
                search: true,
                markSearchResults: true,
                selectedValue: '{{ $team }}'
            });

            teamSelect.disable();

            serviceDepartmentSelect.addEventListener('change', (event) => {
                const serviceDepartmentId = parseInt(event.target.value);
                if (serviceDepartmentId) {
                    @this.set('serviceDepartment', serviceDepartmentId);
                    if (teamSelect) teamSelect.enable();
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
                            if (teamSelect) {
                                teamSelect.setOptions(teamOption);
                                teamSelect.setValue(@json($team));
                            }
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

            teamSelect.addEventListener('change', (event) => {
                const teamId = parseInt(event.target.value);
                @this.set('team', teamId);
            });

            serviceDepartmentSelect.addEventListener('reset', () => {
                const countTeams = document.querySelector('#countTeams');
                @this.set('teams', []);
                @this.set('name', null);
                countTeams.textContent = '';
                document.querySelector('#countTeams').textContent = '';
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
                selectedValue: '{{ $selectedBuDepartment }}'
            });

            VirtualSelect.init({
                ele: approvalLevelSelect,
                options: approvalLevelOption,
                search: true,
                markSearchResults: true,
                selectedValue: '{{ $levelOfApproval }}'
            });

            buDepartmentSelect.addEventListener('change', (event) => {
                @this.set('selectedBuDepartment', parseInt(event.target.value));
            });

            approvalLevelSelect.addEventListener('change', (event) => {
                @this.set('levelOfApproval', parseInt(event.target.value));
            });

            const dynamicApprovalLevelContainer = document.querySelector('#dynamic-approval-container');
            const approvers = {};
            let selectedApprovers = [];

            const initializeApproverSelect = (level) => {
                approvers[`level${level}`] = document.querySelector(
                    `#select-help-topic-approval-level-${level}`);

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
                    @this.call('getFilteredApprovers2', level);
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
                    @this.call('getFilteredApprovers2', i); // Fetch approvers for each level
                }
                window.dispatchEvent(new CustomEvent('approval-level-selected'));
            });

            window.addEventListener('load-approvers2', (event) => {
                const level = event.detail.level;
                const approverSelect = approvers[`level${level}`];

                if (approverSelect) {
                    const approverOptions = event.detail.approvers.map(approver => ({
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

            // Costing Approver
            if (@json($isSpecialProject)) {
                const selectCostingApprover = @json($costingApproversList);
                VirtualSelect.init({
                    ele: '#select-help-topic-costing-approver',
                    options: selectCostingApprover,
                    search: true,
                    multiple: true,
                    showValueAsTags: true,
                    markSearchResults: true,
                    hasOptionDescription: true,
                    selectedValue: @json($costingApprovers)
                });

                const editHelpTopicSelectCostingApprover = document.querySelector(
                    '#select-help-topic-costing-approver');
                editHelpTopicSelectCostingApprover.addEventListener('change', (event) => {
                    const selectedCostingApprovers = event.target.value;
                    @this.set('costingApprovers', selectedCostingApprovers);
                });

                const selectFinalCostingApprover = @json($finalCostingApproversList);
                VirtualSelect.init({
                    ele: '#select-help-topic-final-costing-approver',
                    options: selectFinalCostingApprover,
                    search: true,
                    multiple: true,
                    showValueAsTags: true,
                    markSearchResults: true,
                    hasOptionDescription: true,
                    selectedValue: @json($finalCostingApprovers)
                });

                document.querySelector('#select-help-topic-final-costing-approver').addEventListener('change', (
                    event) => {
                    const selectedFinalCostingApprovers = event.target.value;
                    @this.set('finalCostingApprovers', selectedFinalCostingApprovers);
                });
            }

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
                document.querySelector('#countTeams').textContent = '';
                specialProjectAmountContainer.style.display = 'none';
                teamSelect.disable();
            }
        });

        // Edit config approvers
        window.addEventListener('load-current-configuration', (event) => {
            const editConfigApproverSelect = document.querySelector('#select-edit-config-approvers');
            const currentConfigApproverIds = event.detail.currentConfigApproverIds;
            const buDepartmentApprovers = event.detail.buDepartmentApprovers;
            const helpTopicApprovers = event.detail.helpTopicApprovers;
            const currentConfigLevelOfApproval = event.detail.currentConfigLevelOfApproval;
            const currentConfigurations = event.detail.currentConfigurations;

            const buDepartmentApproversOption = buDepartmentApprovers.map(approver => ({
                label: `${approver.profile.first_name} ${approver.profile.middle_name ? approver.profile.middle_name[0] + '.' : ''} ${approver.profile.last_name}`,
                value: approver.id,
            }));

            VirtualSelect.init({
                ele: editConfigApproverSelect,
                search: true,
                multiple: true,
                showValueAsTags: true,
                markSearchResults: true,
            });

            if (currentConfigApproverIds.length > 0) {
                editConfigApproverSelect.setOptions(buDepartmentApproversOption)
                editConfigApproverSelect.setValue(currentConfigApproverIds)
            }

            editConfigApproverSelect.addEventListener('change', (event) => {
                @this.set('selectedApprovers', event.target.value);
            })
        });
        //
        window.addEventListener('close-confirm-delete-config-modal', () => {
            $('#confirmDeleteCurrentConfigurationModal').modal('hide');
        });

        window.addEventListener('close-update-current-config-modal', () => {
            $('#editCurrentConfigurationModal').modal('hide');
        });
    </script>
@endpush
