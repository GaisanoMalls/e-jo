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
                <form wire:submit.prevent="updateHelpTopic">
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
                                        <label for="serviceDepartment" class="form-label form__field__label">Service
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
                                <div class="col-md-6" id="teamSelectContainer" wire:ignore>
                                    <div class="mb-2">
                                        <label for="team" class="form-label form__field__label">Team <span
                                                class="fw-normal" style="font-size: 13px;"
                                                id="countTeams"></span></label>
                                        <div>
                                            <div id="select-help-topic-team" placeholder="Select (optional)"
                                                wire:ignore></div>
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
                    </div>
                    <hr>
                    <div class="row">
                        <h6 class="fw-semibold mb-4" style="font-size: 0.89rem;">Approval Configurations</h6>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="department" class="form-label form__field__label">BU Department</label>
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
                                <label for="department" class="form-label form__field__label">Level of Approval</label>
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
                    <div style="text-align: left; display: flex; justify-content: flex-start; gap: 10px;">
                        <button
                            class="btn d-flex align-items-center justify-content-center gap-2 btn__add__user__account"
                            style="width: auto; background-color: #d32839; color: white;"
                            wire:click="saveConfiguration">
                            <span>Add</span>
                        </button>
                        <button type="button" class="btn m-0 btn__cancel" onclick="handleCancelApprovalConfig()">
                            Cancel
                        </button>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>BU Department</th>
                                <th>Numbers of Approvers</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($configurations as $index => $config)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $config['bu_department_name'] }}</td>
                                    <td>{{ $config['approvers_count'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center pe-2 gap-1">
                                            <button data-tooltip="Edit" data-tooltip-position="top"
                                                data-tooltip-font-size="11px" type="button" class="btn action__button">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm action__button mt-0"
                                                wire:click="removeConfiguration({{ $index }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No approval configuration added</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Costing Configuration -->
                    <hr>
                    <div class="mt-2" id="specialProjectAmountContainer">
                        <h6 class="fw-semibold mb-4" style="font-size: 0.89rem;">Costing Configurations</h6>
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
                                                    class="form-control form__field max_total_cost" id="amount"
                                                    placeholder="Enter Total Cost">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="costing-approver-container">
                                        <div class="mb-3">
                                            <label class="finalCostingApprover">Final Cost Approver</label>
                                            <div>
                                                <div id="select-help-topic-final-costing-approver" wire:ignore></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal"
                                onclick="window.location.href='{{ route('staff.manage.help_topic.index') }}'">Cancel</button>
                            <button type="submit"
                                class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__details btn__send"
                                style="background-color: #d32839; color: white;" wire:click="saveHelpTopic">
                                <span wire:loading wire:target="updateHelpTopic"
                                    class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                </span>
                                Update
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
                selectedValue: '{{ $approvalLevelSelected }}'
            });

            buDepartmentSelect.addEventListener('change', (event) => {
                @this.set('selectedBuDepartment', event.target.value);
            });

            approvalLevelSelect.addEventListener('change', (event) => {
                @this.set('approvalLevelSelected', event.target.value);
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

            document.querySelector('#select-help-topic-costing-approver').addEventListener('change', (event) => {
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
    </script>
@endpush
