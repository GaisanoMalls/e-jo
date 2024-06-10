<div>
    <div wire:ignore.self class="modal fade account__modal" id="addNewAgentModal" tabindex="-1"
        aria-labelledby="addNewAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewAgentModalLabel">Agent Account</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveAgent">
                    <div class="modal-body modal__body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="d-flex flex-column">
                                    <img src="{{ asset('images/agent_creation.jpg') }}" alt=""
                                        style="height: auto; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <h5 class="mb-4">Fill in the information</h5>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="mb-2">
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="middle_name" class="form-label form__field__label">
                                                    Middle name
                                                </label>
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="last_name" class="form-label form__field__label">Last
                                                    name</label>
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
                                        <div class="col-md-8">
                                            <div class="mb-2">
                                                <label for="suffix" class="form-label form__field__label">
                                                    Suffix
                                                </label>
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="email" class="form-label form__field__label">Email
                                                    address</label>
                                                <input type="email" wire:model.defer="email"
                                                    class="form-control form__field" id="email"
                                                    placeholder="Enter email (required)">
                                                @error('email')
                                                    <span class="error__message">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="branch" class="form-label form__field__label">
                                                    Branch
                                                </label>
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="bu_department" class="form-label form__field__label">
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="department" class="form-label form__field__label">
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label class="form-label form__field__label">
                                                    Team
                                                    @if ($teams)
                                                        <span class="fw-normal" style="font-size: 13px;">
                                                            ({{ $teams->count() }})</span>
                                                    @endif
                                                </label>
                                                <div>
                                                    <div id="select-agent-team" wire:ignore></div>
                                                </div>
                                                @error('teams')
                                                    <span class="error__message">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div wire:ignore class="col-md-12" id="createAgentSubteamContainer">
                                            <div class="mb-2">
                                                <label class="form-label form__field__label">
                                                    Sub-teams
                                                </label>
                                                <div>
                                                    <div id="select-agent-subteam"></div>
                                                </div>
                                                @error('teams')
                                                    <span class="error__message">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 px-2 mt-3">
                                        <div
                                            class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="submit"
                                                    class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                                    <span wire:loading wire:target="saveAgent"
                                                        class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true">
                                                    </span>
                                                    Save
                                                </button>
                                                <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                                                    id="btnCloseModal" data-bs-dismiss="modal"
                                                    wire:click="cancel">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
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
        const agentSuffixOption = @json($agentSuffixes).map(suffix => ({
            label: suffix.name,
            value: suffix.name
        }));

        const agentSuffixSelect = document.querySelector('#select-agent-suffix');
        VirtualSelect.init({
            ele: agentSuffixSelect,
            options: agentSuffixOption,
            search: true,
            markSearchResults: true,
        });

        agentSuffixSelect.addEventListener('change', () => {
            @this.set('suffix', agentSuffixSelect.value)
        });

        const agentBranchOption = @json($agentBranches).map(branch => ({
            label: branch.name,
            value: branch.id
        }));

        const agentBranchSelect = document.querySelector('#select-agent-branch')
        VirtualSelect.init({
            ele: agentBranchSelect,
            options: agentBranchOption,
            search: true,
            markSearchResults: true,
        });

        const agentBUDepartmentSelect = document.querySelector('#select-agent-bu-department')
        VirtualSelect.init({
            ele: agentBUDepartmentSelect,
            search: true,
            markSearchResults: true,
        });
        agentBUDepartmentSelect.disable();

        agentBranchSelect.addEventListener('change', () => {
            const agentBranchId = agentBranchSelect.value;
            if (agentBranchId) {
                @this.set('branch', parseInt(agentBranchId));
                agentBUDepartmentSelect.enable();
                window.addEventListener('get-branch-bu-departments', (event) => {
                    const agentBUDepartments = event.detail.BUDepartments;
                    const agentBUDepartmentOption = [];

                    //  BU/Department Select
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
                    } else {
                        agentBUDepartmentSelect.close();
                        agentBUDepartmentSelect.setOptions();
                        agentBUDepartmentSelect.disable();
                    }
                });
            }
        })

        const agentServiceDepartmentOption = @json($agentServiceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }))

        const agentServiceDepartmentSelect = document.querySelector('#select-agent-service-department');
        VirtualSelect.init({
            ele: agentServiceDepartmentSelect,
            options: agentServiceDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        const agentTeamSelect = document.querySelector('#select-agent-team')
        VirtualSelect.init({
            ele: agentTeamSelect,
            multiple: true,
            search: true,
            showValueAsTags: true,
            markSearchResults: true,
        });
        agentTeamSelect.disable();

        agentServiceDepartmentSelect.addEventListener('change', () => {
            @this.set('service_department', parseInt(agentServiceDepartmentSelect.value));
            agentTeamSelect.enable();

            window.addEventListener('get-teams-service-department', (event) => {
                const agentTeams = event.detail.teams;
                const agentTeamOption = [];

                // Team Select
                if (agentTeams.length > 0) {
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
                } else {
                    agentTeamSelect.close();
                    agentTeamSelect.setOptions();
                    agentTeamSelect.disable();
                }
            });
        });

        const agentSubteamSelect = document.querySelector('#select-agent-subteam')
        const createAgentSubteamContainer = document.querySelector('#createAgentSubteamContainer');

        createAgentSubteamContainer.style.display = 'none';

        VirtualSelect.init({
            ele: agentSubteamSelect,
            multiple: true,
            search: true,
            showValueAsTags: true,
            markSearchResults: true,
            hasOptionDescription: true
        });

        window.addEventListener('get-subteams', (event) => {
            const subteams = event.detail.subteams;
            const subteamOption = [];

            if (subteams.length > 0) {

                subteams.forEach(function(subteam) {
                    subteamOption.push({
                        label: subteam.name,
                        value: subteam.id,
                        description: subteam.team.name
                    });
                });

                createAgentSubteamContainer.style.display = 'block';
                agentSubteamSelect.setOptions(subteamOption);

                agentSubteamSelect.addEventListener('change', () => {
                    @this.set('selectedSubteams', agentSubteamSelect.value);
                });

            } else {
                createAgentSubteamContainer.style.display = 'none';
                agentSubteamSelect.reset();
                agentSubteamSelect.setOptions([]);
            }
        });

        agentServiceDepartmentSelect.addEventListener('reset', () => {
            agentTeamSelect.reset();
            agentTeamSelect.disable();
            agentTeamSelect.setOptions([]);
        });

        agentBranchSelect.addEventListener('reset', () => {
            agentBUDepartmentSelect.reset();
            agentBUDepartmentSelect.disable();
            agentBUDepartmentSelect.setOptions([]);
        });

        agentBUDepartmentSelect.addEventListener('change', () => {
            @this.set('bu_department', parseInt(agentBUDepartmentSelect.value));
        });
        agentTeamSelect.addEventListener('change', () => {
            @this.set('selectedTeams', agentTeamSelect.value);
        });
    </script>
@endpush

@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addNewAgentModal').modal('hide');
            agentSuffixSelect.reset();
            agentBranchSelect.reset();
            agentTeamSelect.reset();
            agentBUDepartmentSelect.reset();
            agentServiceDepartmentSelect.reset();
            agentTeamSelect.disable();
            agentBUDepartmentSelect.disable();
        });
    </script>
@endpush
