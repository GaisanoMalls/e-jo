<div>
    <div class="table-responsive custom__table">
        <table class="table mb-0">
            @if ($buDepartments->isNotEmpty())
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">BU/Department</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Branches</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buDepartments as $department)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $department->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $department->getBranches() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $department->dateCreated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                        data-tooltip-font-size="11px" type="button" class="btn action__button"
                                        data-bs-toggle="modal" data-bs-target="#editBUDepartmentModal"
                                        wire:click="editBUDepartment({{ $department->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteBUDepartmentModal"
                                        wire:click="deleteBUDepartment({{ $department->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @else
                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">No records for BU/Departments.</small>
                </div>
            @endif
        </table>
    </div>

    {{-- Edit BU/Department Modal --}}
    <div wire:ignore.self class="modal fade department__modal" id="editBUDepartmentModal" tabindex="-1"
        aria-labelledby="editBUDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewDepartmentModalLabel">Edit BU/Department</h1>
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
                                    placeholder="Enter BU/department name">
                                @error('name')
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
                                    <div class="error__message mt-1">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </div>
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
                                Update
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete BU/Department Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__bu__department" id="deleteBUDepartmentModal"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h6 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm Delete
                    </h6>
                    <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                        Are you sure you want to delete this BU/Department?
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
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true">
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
            multiple: true,
            required: true,
            showValueAsTags: true,
            markSearchResults: true,
            hasOptionDescription: true,
            popupDropboxBreakpoint: '3000px',
        });

        const editBranchSelect = document.querySelector('#edit-select-branch')
        editBranchSelect.addEventListener('change', () => {
            @this.set('editSelectedBranches', editBranchSelect.value);
        });

        // Check the current branches assigned to the selected BU/Department.
        window.addEventListener('update-branch-select-option', (event) => {
            editBranchSelect.setValue(event.detail.branchIds);
        });

        // Clear the branch select option after update.
        window.addEventListener('clear-branch-select-option', () => {
            editBranchSelect.reset();
        });
    </script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editBUDepartmentModal').modal('hide');
            $('#deleteBUDepartmentModal').modal('hide');
        });

        window.addEventListener('show-edit-bu-department-modal', () => {
            $('#editBUDepartmentModal').modal('show');
        });

        window.addEventListener('show-delete-bu-department-modal', () => {
            $('#deleteBUDepartmentModal').modal('show');
        });
    </script>
@endpush
