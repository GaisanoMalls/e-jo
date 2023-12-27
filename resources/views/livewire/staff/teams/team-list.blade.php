<div>
    <div class="table-responsive custom__table">
        @if (!$teams->isEmpty())
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Branches</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teams as $team)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $team->serviceDepartment->name ?? '----' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
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
                            <div class="mb-2">
                                <label for="name" class="form-label form__field__label">Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control form__field @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter team name">
                                @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
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
        const editServiceDepartmentOption = [
            @foreach ($serviceDepartments as $serviceDepartment)
                {
                    label: "{{ $serviceDepartment->name }}",
                    value: "{{ $serviceDepartment->id }}"
                },
            @endforeach
        ];

        VirtualSelect.init({
            ele: '#edit-select-service-department',
            options: editServiceDepartmentOption,
            search: true,
            required: true,
            markSearchResults: true,
        });

        const editBranchOption = [
            @foreach ($branches as $branch)
                {
                    label: "{{ $branch->name }}",
                    value: "{{ $branch->id }}"
                },
            @endforeach
        ];

        VirtualSelect.init({
            ele: '#edit-select-branch',
            options: editBranchOption,
            search: true,
            required: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
            popupDropboxBreakpoint: '3000px',
        });

        const editServiceDepartmentSelect = document.querySelector('#edit-select-service-department');
        const editBranchSelect = document.querySelector('#edit-select-branch');

        editServiceDepartmentSelect.addEventListener('change', () => {
            @this.set('editSelectedServiceDepartment', editServiceDepartmentSelect.value);
        });

        editBranchSelect.addEventListener('change', () => {
            @this.set('editSelectedBranches', editBranchSelect.value);
        });

        // Clear all selected branches in the select option.
        window.addEventListener('clear-select-options', () => {
            editBranchSelect.reset();
            editServiceDepartmentSelect.reset();
        });

        window.addEventListener('edit-current-service-department-id', (event) => {
            editServiceDepartmentSelect.setValue(event.detail.serviceDepartmentId);
        });

        window.addEventListener('edit-current-branch-ids', (event) => {
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
