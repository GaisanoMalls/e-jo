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
                                        <input type="text" wire:model.defer="name" class="form-control form__field"
                                            id="name" placeholder="Enter name (required)">
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
                                        <label class="form-label form__field__label">
                                            Service Level Agreements (SLA)
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
                                        <label class="form-label form__field__label">Sub-Service Department</label>
                                        <div>
                                            <div id="select-help-topic-service-department-children" wire:ignore></div>
                                        </div>
                                        {{-- @error('service_department')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror --}}
                                    </div>
                                </div>
                                @if (!$helpTopic->specialProject)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label form__field__label">
                                                Team
                                                @if ($teams)
                                                    <span class="fw-normal" style="font-size: 13px;">
                                                        ({{ $teams->count() }})
                                                    </span>
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
                                @endif
                                @if ($helpTopic->specialProject)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="amount"
                                                    class="form-label form__field__label">Amount</label>
                                                <input type="text" wire:model.defer="amount"
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
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                        data-bs-dismiss="modal"
                                        onclick="window.location.href='{{ route('staff.manage.help_topic.index') }}'">Cancel</button>
                                    <button type="submit"
                                        class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__details btn__send">
                                        <span wire:loading wire:target="updateHelpTopic"
                                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                        </span>
                                        Update
                                    </button>
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
        const slaOption = @json($serviceLevelAgreements).map(sla => ({
            label: sla.time_unit,
            value: sla.id
        }));

        const slaSelect = document.querySelector('#select-help-topic-sla');
        VirtualSelect.init({
            ele: slaSelect,
            options: slaOption,
            search: true,
            markSearchResults: true,
            selectedValue: '{{ $helpTopic->service_level_agreement_id }}'
        });

        const serviceDepartmentOption = @json($serviceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }));

        const serviceDepartmentSelect = document.querySelector('#select-help-topic-service-department');
        VirtualSelect.init({
            ele: serviceDepartmentSelect,
            options: serviceDepartmentOption,
            search: true,
            markSearchResults: true,
            selectedValue: @json($service_department)
        });

        const serviceDeptChildrenOption = @json($serviceDepartmentChildren).map(child => ({
            label: child.name,
            value: child.id
        }));

        const serviceDepartmentChildrenSelect = document.querySelector('#select-help-topic-service-department-children');
        VirtualSelect.init({
            ele: serviceDepartmentChildrenSelect,
            options: serviceDeptChildrenOption,
            search: true,
            markSearchResults: true,
            selectedValue: @json($service_department_child?->id)
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
            selectedValue: @json($team)
        });

        serviceDepartmentSelect.addEventListener('change', () => {
            const serviceDepartmentId = parseInt(serviceDepartmentSelect.value);
            if (serviceDepartmentId) {
                @this.set('service_department', serviceDepartmentId);
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

        serviceDepartmentChildrenSelect.addEventListener('change', () => {
            @this.set('selected_child', serviceDepartmentChildrenSelect.value);
            @this.set('selectedServiceDepartmentChildrenName', serviceDepartmentChildrenSelect.getDisplayValue());
        });

        const isSpecialProject = @json($isSpecialProject);
        if (isSpecialProject) {
            serviceDepartmentSelect.addEventListener('change', () => {
                const serviceDepartments = @json($serviceDepartments);

                serviceDepartments.forEach((department) => {
                    if (serviceDepartmentSelect.value == department.id) {
                        @this.set('name', `(SP) ${department.name}`);
                    }
                });
            });
        }

        slaSelect.addEventListener('reset', () => {
            @this.set('sla', null);
        });

        serviceDepartmentSelect.addEventListener('reset', () => {
            @this.set('service_department', null);
            @this.set('teams', []); // Clear teams count when service department is resetted.
        });

        slaSelect.addEventListener('change', () => {
            const slaId = parseInt(slaSelect.value);
            @this.set('sla', slaId);
        });

        if (teamSelect) {
            teamSelect.addEventListener('change', () => {
                const teamId = parseInt(teamSelect.value);
                @this.set('team', teamId);
            });
        }
    </script>
@endpush
