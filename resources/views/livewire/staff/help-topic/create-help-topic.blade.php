<div>
    <div class="row justify-content-center help__topics__section">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 help__topic__details__card">
                <div class="help__topic__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Add New Help Topic</h6>
                </div>
                <div class="row gap-4 help__topic__details__container">
                    <p>*temporarily removed the fields here*</p>
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
                    <button class="btn d-flex align-items-center justify-content-center gap-2 m-0"
                        wire:click="saveConfiguration">
                        Save Configuration
                    </button>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>BU Department</th>
                                <th>Numbers of Approvers</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($configurations as $index => $config)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $config['bu_department_name'] }}</td>
                                    <td>{{ $config['approvers_count'] }}</td>
                                    <td>
                                        <button wire:click="removeConfiguration({{ $index }})"
                                            class="btn btn-danger">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                            <span wire:loading wire:target="saveHelpTopic" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Add New
                        </button>
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal" wire:click="cancel">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
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

        buDepartmentSelect.addEventListener('change', () => {
            @this.set('selectedBuDepartment', buDepartmentSelect.value);
        });

        approvalLevelSelect.addEventListener('change', () => {
            @this.set('approvalLevelSelected', true);
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
    </script>
@endpush
