<div>
    @livewire('staff.accounts.agent.update-agent-password', ['agent' => $agent])
    <div class="row accounts__section justify-content-center">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 users__account__card">
                <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Agent's Information</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated:
                        @if ($agent->dateUpdated() > $agent->profile->dateUpdated())
                            {{ $agent->dateUpdated() }}
                        @else
                            {{ $agent->profile->dateUpdated() }}
                        @endif
                    </small>
                </div>
                <form wire:submit.prevent="updateAgentAccount">
                    <div class="row gap-4 user__details__container">
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label form__field__label">First
                                            name</label>
                                        <input type="text" wire:model.defer="first_name"
                                            class="form-control form__field" id="first_name"
                                            placeholder="Enter first name (required)">
                                        @error('first_name')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label form__field__label">Middle
                                            name</label>
                                        <input type="text" wire:model.defer="middle_name"
                                            class="form-control form__field" id="middle_name"
                                            placeholder="Enter middle name (optional)">
                                        @error('middle_name')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label form__field__label">Last name</label>
                                        <input type="text" wire:model.defer="last_name"
                                            class="form-control form__field" id="last_name"
                                            placeholder="Enter last name (required)">
                                        @error('last_name')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Suffix</label>
                                        <div>
                                            <div id="select-agent-suffix" wire:ignore></div>
                                        </div>
                                        @error('suffix')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Login Credentials</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label form__field__label">Email</label>
                                        <input type="email" wire:model.defer="email" class="form-control form__field"
                                            id="email" placeholder="Enter email (required)">
                                        @error('email')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="button"
                                            class="btn m-0 btn__details btn__update__password d-flex align-items-center gap-2 justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                            <i class="fa-solid fa-key"></i>
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Work Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Branch</label>
                                        <div>
                                            <div id="select-agent-branch" wire:ignore></div>
                                        </div>
                                        @error('branch')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" value="{{ $agent->department_id }}"
                                            id="agentCurrentBUDepartmentId">
                                        <label class="form-label form__field__label">
                                            BU/Department
                                            @if ($BUDepartments)
                                                <span class="fw-normal" style="font-size: 13px;">
                                                    ({{ $BUDepartments->count() }})</span>
                                            @endif
                                        </label>
                                        <div>
                                            <div id="select-agent-bu-department" wire:ignore></div>
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
                                    <div class="mb-3">
                                        <label for="branch" class="form-label form__field__label">
                                            Service Department
                                        </label>
                                        <div>
                                            <div id="select-agent-service-department" wire:ignore></div>
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
                                                    ({{ $teams->count() }})
                                                </span>
                                            @endif
                                        </label>
                                        <div>
                                            <div id="select-agent-team" wire:ignore></div>
                                        </div>
                                        @error('selectedTeams')
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
                                            Subteams
                                        </label>
                                        <div>
                                            <div id="select-agent-subteam" wire:ignore></div>
                                        </div>
                                        @error('selectedSubteams')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                    data-bs-dismiss="modal"
                                    onclick="window.location.href='{{ route('staff.manage.user_account.agents') }}'">Back</button>
                                <button type="submit"
                                    class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__details btn__send">
                                    <span wire:loading wire:target="updateAgentAccount"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Save
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
        const agentSuffixOption = [
            @foreach ($agentSuffixes as $suffix)
                {
                    label: "{{ $suffix->name }}",
                    value: "{{ $suffix->name }}"
                },
            @endforeach
        ];

        const agentSuffixSelect = document.querySelector('#select-agent-suffix');
        VirtualSelect.init({
            ele: agentSuffixSelect,
            options: agentSuffixOption,
            search: true,
            markSearchResults: true,
            selectedValue: '{{ $agent->profile->suffix }}'
        });

        const agentBranchOption = [
            @foreach ($agentBranches as $br)
                {
                    label: "{{ $br->name }}",
                    value: "{{ $br->id }}"
                },
            @endforeach
        ];

        const agentBranchSelect = document.querySelector('#select-agent-branch');
        VirtualSelect.init({
            ele: agentBranchSelect,
            options: agentBranchOption,
            search: true,
            markSearchResults: true,
            selectedValue: @json($branch)
        });

        agentBranchSelect.addEventListener('reset', () => {
            @this.set('branch', null);
            @this.set('bu_department', null);
            @this.set('selectedTeams', []);
            agentBUDepartmentSelect.reset();
            agentBUDepartmentSelect.disable();
            agentBUDepartmentSelect.setOptions([]);

        });

        agentBranchSelect.addEventListener('change', () => {
            const agentBranchId = agentBranchSelect.value;
            if (agentBranchId) {
                @this.set('branch', parseInt(agentBranchId));
                agentBUDepartmentSelect.enable();

                window.addEventListener('get-branch-bu-departments', (event) => {
                    const agentBUDepartments = event.detail.BUDepartments;
                    const agentBUDepartmentOption = [];

                    // BU/Department Select
                    if (agentBUDepartments.length > 0) {
                        agentBUDepartments.forEach(function(agentBUDepartment) {
                            VirtualSelect.init({
                                ele: agentBUDepartmentSelect
                            });

                            agentBUDepartmentOption.push({
                                label: agentBUDepartment.name,
                                value: agentBUDepartment.id
                            });
                        });
                        agentBUDepartmentSelect.setOptions(agentBUDepartmentOption);
                        agentBUDepartmentSelect.setValue(@json($bu_department));
                    }
                })
            }
        });

        const agentBUDepartmentOption = [
            @foreach ($agentBUDepartments as $department)
                {
                    label: "{{ $department->name }}",
                    value: "{{ $department->id }}"
                },
            @endforeach
        ];

        const agentBUDepartmentSelect = document.querySelector('#select-agent-bu-department');
        VirtualSelect.init({
            ele: agentBUDepartmentSelect,
            options: agentBUDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        agentBUDepartmentSelect.addEventListener('change', () => {
            @this.set('bu_department', parseInt(agentBUDepartmentSelect.value));
        });

        const agentTeamOption = [
            @foreach ($agentTeams as $team)
                {
                    label: "{{ $team->name }}",
                    value: "{{ $team->id }}"
                },
            @endforeach
        ];

        const agentTeamSelect = document.querySelector('#select-agent-team');
        VirtualSelect.init({
            ele: agentTeamSelect,
            options: agentTeamOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
        });

        const agentSubteamOption = [
            @foreach ($agentSubteams as $subteam)
                {
                    label: "{{ $subteam->name }}",
                    value: "{{ $subteam->id }}",
                    description: "{{ $subteam->team->name }}"
                },
            @endforeach
        ];

        const agentSubteamSelect = document.querySelector('#select-agent-subteam');
        VirtualSelect.init({
            ele: agentSubteamSelect,
            options: agentSubteamOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
            hasOptionDescription: true,
        });

        const agentServiceDepartmentOption = [
            @foreach ($agentServiceDepartments as $serviceDepartment)
                {
                    label: "{{ $serviceDepartment->name }}",
                    value: "{{ $serviceDepartment->id }}"
                },
            @endforeach
        ];

        const agentServiceDepartmentSelect = document.querySelector('#select-agent-service-department');
        VirtualSelect.init({
            ele: agentServiceDepartmentSelect,
            options: agentServiceDepartmentOption,
            search: true,
            markSearchResults: true,
            selectedValue: @json($service_department)
        });

        agentServiceDepartmentSelect.addEventListener('change', () => {
            serviceDepartmentId = agentServiceDepartmentSelect.value;

            if (serviceDepartmentId) {
                @this.set('service_department', parseInt(serviceDepartmentId));
                agentBUDepartmentSelect.enable();

                window.addEventListener('get-teams-service-department', () => {
                    const agentTeams = event.detail.teams;
                    const agentTeamOption = [];

                    // Teams Select
                    if (agentTeams.length > 0) {
                        agentTeamSelect.enable();

                        agentTeams.forEach(function(agentTeam) {
                            VirtualSelect.init({
                                ele: agentTeamSelect
                            });

                            agentTeamOption.push({
                                label: agentTeam.name,
                                value: agentTeam.id
                            });
                        });
                        agentTeamSelect.setOptions(agentTeamOption);
                        agentTeamSelect.setValue(@json($currentTeams));
                    } else {
                        agentTeamSelect.reset();
                        agentTeamSelect.disable();
                        agentTeamSelect.setOptions([]);
                    }
                });
            }
        });

        agentTeamSelect.addEventListener('change', () => {
            @this.set('selectedTeams', agentTeamSelect.value);

            window.addEventListener('get-subteams', (event) => {
                const subteams = event.detail.subteams;
                const subteamsOption = [];

                if (subteams.length > 0) {
                    subteams.forEach(function(subteam) {
                        subteamsOption.push({
                            label: subteam.name,
                            value: subteam.id,
                            description: subteam.team.name
                        });
                    });

                    agentSubteamSelect.setOptions(subteamsOption);
                    agentSubteamSelect.setValue(@json($currentSubteams));

                } else {
                    agentSubteamSelect.reset()
                    agentSubteamSelect.setOptions()
                }
            });
        });

        agentSubteamSelect.addEventListener('change', () => {
            @this.set('selectedSubteams', agentSubteamSelect.value);
        });

        agentTeamSelect.addEventListener('reset', () => {
            agentSubteamSelect.reset();
        });

        agentServiceDepartmentSelect.addEventListener('reset', () => {
            agentTeamSelect.reset();
            agentTeamSelect.disable();
            agentTeamSelect.setOptions([]);
            agentTeamSelect.setOptions([]);
        })
    </script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editPasswordModal').modal('hide');
        });
    </script>
@endpush
