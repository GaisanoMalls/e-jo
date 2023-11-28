<div>
    <div wire:ignore.self class="modal fade account__modal" id="addNewApproverModal" tabindex="-1"
        aria-labelledby="addNewApproverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewApproverModalLabel">Add new approver</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveApprover">
                    <div class="modal-body modal__body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="d-flex flex-column">
                                    <img src="{{ asset('images/approver_creation.jpg') }}" alt=""
                                        style="height: auto; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <h5 class="mb-4">Fill in the information</h5>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="first_name" class="form-label form__field__label">
                                                    First name
                                                </label>
                                                <input type="text" wire:model="first_name"
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
                                                <input type="text" wire:model="middle_name"
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
                                                <input type="text" wire:model="last_name"
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
                                                    <div id="select-approver-suffix" wire:ignore></div>
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
                                                <input type="email" wire:model="email" class="form-control form__field"
                                                    id="email" placeholder="Enter email (required)">
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
                                                <input type="text" value="Approver" class="form-control form__field"
                                                    disabled readonly
                                                    style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="branch" class="form-label form__field__label">Branch</label>
                                                <div>
                                                    <div id="select-approver-branch" wire:ignore></div>
                                                </div>
                                                @error('branches')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="branch" class="form-label form__field__label">
                                                    BU/Department
                                                </label>
                                                <div>
                                                    <div id="select-approver-bu-department" wire:ignore></div>
                                                </div>
                                                @error('bu_departments')
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
                                                    <span wire:loading wire:target="saveApprover"
                                                        class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true">
                                                    </span>
                                                    Save
                                                </button>
                                                <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                                                    id="btnCloseModal" data-bs-dismiss="modal">Cancel</button>
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
    const approverSuffixOption = [
        @foreach ($approverSuffixes as $suffix)
            {
                label: "{{ $suffix->name }}",
                value: "{{ $suffix->name }}"
            },
        @endforeach
    ];

    const approverSuffixSelect = document.querySelector('#select-approver-suffix');
    VirtualSelect.init({
        ele: approverSuffixSelect,
        options: approverSuffixOption,
        search: true,
        markSearchResults: true,
    });

    approverSuffixSelect.addEventListener('change', () => {
        @this.set('suffix', approverSuffixSelect.value);
    });

    const approverBranchOption = [
        @foreach ($approverBranches as $branch)
        {
            label: "{{ $branch->name }}",
            value: "{{ $branch->id }}"
        },
        @endforeach
    ];

    const approverBranchSelect = document.querySelector('#select-approver-branch');
    VirtualSelect.init({
        ele: approverBranchSelect,
        options: approverBranchOption,
        search: true,
        multiple: true,
        showValueAsTags: true,
        markSearchResults: true,
    });

    approverBranchSelect.addEventListener('change', () => {
        @this.set('branches', approverBranchSelect.value);
    });

    const approverBUDepartmentOption = [
        @foreach ($approverBUDepartments as $department)
        {
            label: "{{ $department->name }}",
            value: "{{ $department->id }}"
        },
        @endforeach
    ];

    const approverBUDepartmentSelect = document.querySelector('#select-approver-bu-department');
    VirtualSelect.init({
        ele: approverBUDepartmentSelect,
        options: approverBUDepartmentOption,
        search: true,
        multiple: true,
        showValueAsTags: true,
        markSearchResults: true,
    });
    approverBUDepartmentSelect.addEventListener('change', () => {
        @this.set('bu_departments', approverBUDepartmentSelect.value);
    });
</script>
@endpush

@push('livewire-modal')
<script>
    window.addEventListener('close-modal', () =>{
        $('#addNewApproverModal').modal('hide');
        approverBranchSelect.reset();
        approverBUDepartmentSelect.reset();
    });
</script>
@endpush