<div>
    <div wire:ignore.self class="modal fade account__modal" id="addNewUserModal" tabindex="-1"
        aria-labelledby="addNewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewUserModalLabel">Requester Account</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveRequester">
                    <div class="modal-body modal__body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="d-flex flex-column">
                                    <img src="{{ asset('images/user_creation.jpg') }}" alt=""
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
                                                    placeholder="Enter middle name (required)">
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
                                                    <div id="select-requester-suffix" wire:ignore></div>
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
                                    <div class="col-md-6">
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
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="role" class="form-label form__field__label">User
                                                    role</label>
                                                <input type="text" value="User / Requester"
                                                    class="form-control form__field" disabled readonly
                                                    style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="branch" class="form-label form__field__label">
                                                    Branch
                                                </label>
                                                <div>
                                                    <div id="select-requester-branch" wire:ignore></div>
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
                                                <label for="department" class="form-label form__field__label">
                                                    BU/Department
                                                    @if ($BUDepartments)
                                                        <span class="fw-normal" style="font-size: 13px;"
                                                            id="requesterQueryCountBUDepartment">
                                                            ({{ $BUDepartments->count() }})</span>
                                                    @endif
                                                </label>
                                                <div>
                                                    <div id="select-requester-bu-department" wire:ignore></div>
                                                </div>
                                                @error('department')
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
                                                    <span wire:loading wire:target="saveRequester"
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
        const requesterSuffixOption = [
            @foreach ($requesterSuffixes as $suffix)
                {
                    label: "{{ $suffix->name }}",
                    value: "{{ $suffix->name }}"
                },
            @endforeach
        ];

        const requesterSuffixSelect = document.querySelector('#select-requester-suffix');
        VirtualSelect.init({
            ele: requesterSuffixSelect,
            options: requesterSuffixOption,
            search: true,
            markSearchResults: true,
        });

        requesterSuffixSelect.addEventListener('change', () => {
            @this.set('suffix', requesterSuffixSelect.value);
        })

        const requesterBranchOption = [
            @foreach ($requesterBranches as $branch)
                {
                    label: "{{ $branch->name }}",
                    value: "{{ $branch->id }}"
                },
            @endforeach
        ];

        const requesterBranchSelect = document.querySelector('#select-requester-branch')
        VirtualSelect.init({
            ele: requesterBranchSelect,
            options: requesterBranchOption,
            search: true,
            markSearchResults: true,
        });

        const requesterBUDepartmentSelect = document.querySelector('#select-requester-bu-department')
        VirtualSelect.init({
            ele: requesterBUDepartmentSelect,
            search: true,
            markSearchResults: true,
        });
        requesterBUDepartmentSelect.disable();

        requesterBranchSelect.addEventListener('change', () => {
            const requesterBranchId = requesterBranchSelect.value;
            if (requesterBranchId) {
                @this.set('branch', parseInt(requesterBranchId));
                requesterBUDepartmentSelect.enable();
                window.addEventListener('get-branch-bu-departments', (event) => {
                    const requesterBUDepartments = event.detail.BUDepartments;
                    const requesterBUDepartmentOption = [];

                    if (requesterBUDepartments.length > 0) {
                        requesterBUDepartments.forEach(function(requesterBUDepartment) {
                            VirtualSelect.init({
                                ele: requesterBUDepartmentSelect
                            });

                            requesterBUDepartmentOption.push({
                                label: requesterBUDepartment.name,
                                value: requesterBUDepartment.id
                            });
                        });
                        requesterBUDepartmentSelect.setOptions(requesterBUDepartmentOption);
                    } else {
                        requesterBUDepartmentSelect.close();
                        requesterBUDepartmentSelect.setOptions([]);
                        requesterBUDepartmentSelect.disable();
                    }
                });
            }
        });

        requesterBUDepartmentSelect.addEventListener('change', () => {
            @this.set('department', parseInt(requesterBUDepartmentSelect.value));
        });

        requesterBranchSelect.addEventListener('reset', () => {
            requesterBUDepartmentSelect.reset();
            requesterBUDepartmentSelect.disable();
            requesterBUDepartmentSelect.setOptions([]);
        });
    </script>
@endpush

@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addNewUserModal').modal('hide');
            requesterSuffixSelect.reset();
            requesterBranchSelect.reset();
            requesterBUDepartmentSelect.reset();
            requesterBUDepartmentSelect.disable();
        });
    </script>
@endpush
