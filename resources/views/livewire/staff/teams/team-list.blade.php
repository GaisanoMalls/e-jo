<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
        <h6 class="mb-0 table__name shadow">List of teams</h6>
        <div class="d-flex align-items-center justify-content-center ">
            <div class="d-flex flex-column flex-wrap mx-4 gap-1 position-relative">
                <div class="w-100 d-flex align-items-center position-relative">
                    <input wire:model.debounce.400ms="searchTeam" type="text" class="form-control table__search__field"
                        placeholder="Search permission">
                    <i wire:loading.remove wire:target="searchTeam"
                        class="fa-solid fa-magnifying-glass table__search__icon"></i>
                    <span wire:loading wire:target="searchTeam"
                        class="spinner-border spinner-border-sm table__search__icon" role="status" aria-hidden="true">
                    </span>
                </div>
                @if (!empty($searchTeam))
                    <div class="w-100 d-flex align-items-center gap-2 mb-1 position-absolute"
                        style="font-size: 0.9rem; bottom: -25px;">
                        <small class="text-muted">
                            {{ $teams->count() }} {{ $teams->count() > 1 ? 'results' : 'result' }} found
                        </small>
                        <small wire:click="clearSearch" class="fw-regular text-danger clear__search">Clear</small>
                    </div>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                    data-bs-toggle="modal" data-bs-target="#addNewTeamModal">
                    <i class="fa-solid fa-plus"></i>
                    <span class="button__name">Add New</span>
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive custom__table">
        @if ($teams)
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Sub-teams</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Sub-Service Dept.</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Branches</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teams as $team)
                        <tr wire:key="team-{{ $team->id }}">
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->getSubteams() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->serviceDepartment->name ?? '' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->serviceDepartmentChild?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    @if ($team->branches->count() !== 0)
                                        <span
                                            class="d-flex align-items-center justify-content-center rounded-circle text-muted me-2"
                                            style="height: 20px; width: 20px; font-size: 11px; padding: 0.6rem; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                            {{ $team->branches->count() }}
                                        </span>
                                    @endif
                                    <span>{{ $team->getBranches() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->dateCreated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->dateUpdated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                        data-tooltip-font-size="11px" type="button" class="btn action__button"
                                        data-bs-toggle="modal" data-bs-target="#editTeamModal"
                                        wire:click="editTeam({{ $team->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteTeamModal" wire:click="deleteTeam({{ $team->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">No records for teams.</small>
            </div>
        @endif
        <div class="mt-3 mx-4 d-flex flex-wrap align-items-center justify-content-between">
            <small class="text-muted" style="margin-bottom: 20px; font-size: 0.82rem;">
                Showing {{ $teams->firstItem() }}
                to {{ $teams->lastItem() }}
                of {{ $teams->total() }} results
            </small>
            {{ $teams->links() }}
        </div>
    </div>


    {{-- Edit Team Modal --}}
    <div wire:ignore.self class="modal fade team__modal" id="editTeamModal" tabindex="-1"
        aria-labelledby="editTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewTeamModalLabel">Edit team</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            @if (!$this->isCurrentTeamHasSubteams())
                                <div class="col-12 mb-3 d-flex">
                                    <input wire:model="hasSubteam" class="form-check-input check__special__project"
                                        type="checkbox" role="switch" id="checkHasSubteam"
                                        wire:loading.attr="disabled">
                                    <label class="form-check-label" for="checkHasSubteam"
                                        style="margin-top: 0.2rem !important;">
                                        Has subteam
                                    </label>
                                </div>
                            @endif
                            <div class="mb-2" style="z-index: 2">
                                <label for="name" class="form-label form__field__label">Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control form__field @error('name') is-invalid @enderror"
                                    id="name" placeholder="Enter team name">
                                @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            @if ($hasSubteam || $this->isCurrentTeamHasSubteams())
                                <div class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                    style="height: 93px; width: 88%; margin-left: 40px; margin-top: -25px; z-index: 1;">
                                    <div class="d-flex mt-2 align-items-center justify-content-between gap-2">
                                        <label for="subteamInput" class="form-label mt-1 form__field__label">
                                            Add sub-team
                                        </label>
                                        @error('subteam')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="position-relative">
                                        <input type="text" wire:model="subteam"
                                            class="form-control position-relative pe-5 form__field @error('subteam') 'is-invalid' @enderror"
                                            placeholder="Enter subteam name" style="width: 100%;" id="subteamInput">
                                        <button wire:click="addSubteam" type="button"
                                            class="btn btn-sm d-flex align-items-center justify-content-center outline-none rounded-3 position-absolute"
                                            style="right: 0.6rem; top: 0.5rem; height: 30px; width: 30px; background-color: #edeef0; border: 1px solid #e7e9eb;">
                                            <span wire:loading.remove wire:target="addSubteam">
                                                <i class="bi bi-save"></i>
                                            </span>
                                            <div wire:loading wire:target="addSubteam"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- Newly added children --}}
                            @if (!empty($addedSubteams))
                                @foreach (collect($this->addedSubteams) as $key => $subteam)
                                    <div wire:key="subteam-{{ $key }}"
                                        class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                        style="height: 60px; width: 88%; margin-left: 40px; margin-top: -25px; z-index: 0;">
                                        <div class="position-relative">
                                            <input type="text" readonly value="{{ $subteam }}"
                                                class="form-control position-relative pe-5 form__field"
                                                style="width: 100%; margin-top: 11px; background-color: #f9fbfc;">
                                            <div class="d-flex align-items-center justify-content-center bg-white p-3 rounded-circle position-absolute"
                                                style="right: -0.5rem; top: -0.5rem; height: 30px; width: 30px;">
                                                <button wire:click="removeSubteam({{ $key }})"
                                                    type="button"
                                                    class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle"
                                                    style="height: 27px; width: 27px; font-size: 0.75rem; color: #d32839; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if ($this->subteams()?->isNotEmpty())
                                @foreach ($this->subteams() as $subteam)
                                    <div wire:key="subteam-{{ $subteam->id }}"
                                        class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                        style="height: 60px; width: 88%; margin-left: 40px; margin-top: -25px; z-index: 0;">
                                        @if ($subteamEditId === $subteam->id)
                                            <div wire:key="update-{{ $subteam->id }}" class="position-relative">
                                                <input wire:model="subteamEditName" type="text"
                                                    class="form-control position-relative pe-5 form__field"
                                                    style="width: 100%; margin-top: 11px; {{ $subteamEditId === $subteam->id ? 'border: 1px solid #D32839;' : '' }}">
                                                <div class="d-flex align-items-center gap-1 bg-white rounded-4 p-1 position-absolute"
                                                    style="right: -0.5rem; top: -0.5rem;">
                                                    <button wire:click="updateSubteam({{ $subteam->id }})"
                                                        type="button"
                                                        class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle"
                                                        style="height: 27px; width: 27px; font-size: 0.75rem; color: #d32839; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                                        <i wire:loading.remove
                                                            wire:target="updateSubteam({{ $subteam->id }})"
                                                            class="bi bi-check-lg"></i>
                                                        <i wire:loading wire:target="updateChild({{ $subteam->id }})"
                                                            class='bx bx-loader-alt bx-spin'></i>
                                                    </button>
                                                    <button wire:click="cancelEditSubteam({{ $subteam->id }})"
                                                        type="button"
                                                        class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle"
                                                        style="height: 27px; width: 27px; font-size: 0.75rem; color: #d32839; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div wire:key="edit-{{ $subteam->id }}" class="position-relative">
                                                <input type="text" readonly value="{{ $subteam->name }}"
                                                    class="form-control position-relative pe-5 form__field"
                                                    style="width: 100%; margin-top: 11px;">
                                                <div class="d-flex align-items-center gap-1 bg-white rounded-4 p-1 position-absolute"
                                                    style="right: -0.5rem; top: -0.5rem;">
                                                    <button wire:click="editSubteam({{ $subteam->id }})"
                                                        type="button"
                                                        class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle"
                                                        style="height: 27px; width: 27px; font-size: 0.75rem; color: #d32839; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button wire:click="deleteSubteam({{ $subteam->id }})"
                                                        type="button"
                                                        class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle"
                                                        style="height: 27px; width: 27px; font-size: 0.75rem; color: #d32839; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            <div class="mb-2" style="z-index: 2;">
                                <label class="form-label form__field__label">Service Department</label>
                                <div>
                                    <div id="edit-select-service-department" wire:ignore></div>
                                </div>
                                @error('editSelectedServiceDepartment')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div wire:ignore class="ps-4 pe-0 pt-4 border-start border-bottom position-relative"
                                style="height: 76px; width: 88%; margin-bottom: 1.7rem; margin-left: 40px; margin-top: -8px; border-bottom-left-radius: 10px;"
                                id="editSelectServiceDeptChildrenContainer">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <label for="childInput" class="form-label form__field__label">
                                        Select Sub-Service Department
                                    </label>
                                    @error('selectedServiceDeptChild')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="position-relative">
                                    <div>
                                        <div id="edit-select-service-department-children" wire:ignore></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label form__field__label">Assign to branch</label>
                                <div>
                                    <div id="edit-select-branch" wire:ignore></div>
                                </div>
                                @error('editSelectedBranches')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="update" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Add New
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" wire:click="cancel"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Team Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__team" id="deleteTeamModal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h6 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm Delete
                    </h6>
                    <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                        Are you sure you want to delete this team?
                    </p>
                    <strong>{{ $name }}</strong>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                        wire:click="delete">
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true">
                        </span>
                        Yes, delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        const editServiceDepartmentSelect = document.querySelector('#edit-select-service-department');
        const editBranchSelect = document.querySelector('#edit-select-branch');
        const editServiceDepartmentChildSelect = document.querySelector('#edit-select-service-department-children');
        const editSelectServiceDeptChildrenContainer = document.querySelector('#editSelectServiceDeptChildrenContainer');

        editSelectServiceDeptChildrenContainer.style.display = 'none';

        const editServiceDepartmentOption = @json($serviceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }));

        VirtualSelect.init({
            ele: editServiceDepartmentSelect,
            options: editServiceDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        const editBranchOption = @json($branches).map(branch => ({
            label: branch.name,
            value: branch.id
        }));

        VirtualSelect.init({
            ele: editBranchSelect,
            options: editBranchOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
        });

        VirtualSelect.init({
            ele: editServiceDepartmentChildSelect,
            search: true,
            markSearchResults: true,
        });

        editBranchSelect.addEventListener('change', (event) => {
            @this.set('editSelectedBranches', event.target.value);
        });

        // Clear all selected branches in the select option.
        window.addEventListener('clear-select-options', () => {
            editBranchSelect.reset();
            editServiceDepartmentSelect.reset();
        });

        window.addEventListener('edit-current-service-department', (event) => {
            editServiceDepartmentSelect.setValue(event.detail.serviceDepartmentId);
        });

        editServiceDepartmentSelect.addEventListener('reset', () => {
            editServiceDepartmentChildSelect.setOptions([])
            editSelectServiceDeptChildrenContainer.style.display = 'none';
        });

        editServiceDepartmentSelect.addEventListener('change', (event) => {
            const serviceDeptId = parseInt(event.target.value);

            if (serviceDeptId) {
                @this.set('editSelectedServiceDepartment', serviceDeptId);
            }
        });

        window.addEventListener('edit-current-service-department-children', (event) => {
            const serviceDepartmentChildren = event.detail.serviceDepartmentChildren;
            const currentServiceDeptChild = event.detail.currentServiceDeptChild;
            const editServiceDeptChildrenOption = [];
            console.log(currentServiceDeptChild);

            if (serviceDepartmentChildren.length > 0) {
                editSelectServiceDeptChildrenContainer.style.display = 'block';

                serviceDepartmentChildren.forEach(function(child) {
                    editServiceDeptChildrenOption.push({
                        label: child.name,
                        value: child.id
                    });
                });

                editServiceDepartmentChildSelect.setOptions(editServiceDeptChildrenOption);
                editServiceDepartmentChildSelect.setValue(currentServiceDeptChild);

                editServiceDepartmentChildSelect.addEventListener('change', (event) => {
                    @this.set('selectedServiceDeptChild', event.target.value);
                });

            } else {
                // If no children, hide the child select
                editSelectServiceDeptChildrenContainer.style.display = 'none';
            }
        });


        window.addEventListener('edit-current-branches', (event) => {
            editBranchSelect.setValue(event.detail.branchIds);
        });

        window.addEventListener('reset-select-options', () => {
            editServiceDepartmentSelect.reset();
            editBranchSelect.reset();
        });
    </script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editTeamModal').modal('hide');
            $('#deleteTeamModal').modal('hide');
        });

        window.addEventListener('show-edit-team-modal', () => {
            $('#editTeamModal').modal('show');
        });

        window.addEventListener('show-delete-team-modal', () => {
            $('#deleteTeamModal').modal('show');
        });
    </script>
@endpush
