<div>
    <div wire:ignore.self class="modal fade account__modal" id="addNewServiceDeptAdminModal" tabindex="-1"
        aria-labelledby="addNewDeptAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewDeptAdminModalLabel">
                        Service Department Admin Account
                    </h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveServiceDepartmentAdmin">
                    <div class="modal-body modal__body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="d-flex flex-column">
                                    <img src="{{ asset('images/department_admin_creation.jpg') }}" alt=""
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
                                                    <div id="select-service-dept-admin-suffix" wire:ignore></div>
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
                                                <input type="email" wire:model="email"
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
                                                <label for="role" class="form-label form__field__label">User
                                                    role</label>
                                                <input type="text" value="Department Admin"
                                                    class="form-control form__field" disabled readonly
                                                    style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="branch"
                                                    class="form-label form__field__label">Branch</label>
                                                <div>
                                                    <div id="select-service-dept-admin-branch" wire:ignore></div>
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
                                                <label for="department" class="form-label form__field__label">
                                                    BU/Department
                                                    @if ($BUDepartments)
                                                        <span class="fw-normal" style="font-size: 13px;">
                                                            ({{ $BUDepartments->count() }})</span>
                                                    @endif
                                                </label>
                                                <div>
                                                    <div id="select-service-dept-admin-bu-department" wire:ignore></div>
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
                                                    <div id="select-service-dept-admin-service-department" wire:ignore>
                                                    </div>
                                                </div>
                                                @error('service_departments')
                                                    <span class="error__message">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if (!$hasCostingApprover1)
                                            <div class="col-12 mb-3 d-flex">
                                                <input wire:model="asCostingApprover1"
                                                    class="form-check-input check__special__project" type="checkbox"
                                                    role="switch" id="checkCostingApprover1"
                                                    wire:loading.attr="disabled">
                                                <label class="form-check-label" for="checkCostingApprover1">
                                                    Add as costing approver 1
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-5 px-2 mt-3">
                                        <div
                                            class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="submit"
                                                    class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                                    <span wire:loading wire:target="saveServiceDepartmentAdmin"
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
        const serviceDeptAdminSuffixOption = [
            @foreach ($serviceDeptAdminSuffixes as $suffix)
                {
                    label: "{{ $suffix->name }}",
                    value: "{{ $suffix->name }}"
                },
            @endforeach
        ];

        const serviceDeptAdminSuffixSelect = document.querySelector('#select-service-dept-admin-suffix');
        VirtualSelect.init({
            ele: serviceDeptAdminSuffixSelect,
            options: serviceDeptAdminSuffixOption,
            search: true,
            markSearchResults: true,
        });

        serviceDeptAdminSuffixSelect.addEventListener('change', () => {
            @this.set('suffix', serviceDeptAdminSuffixSelect.value)
        });

        const serviceDeptAdminBranchOption = [
            @foreach ($serviceDeptAdminBranches as $branch)
                {
                    label: "{{ $branch->name }}",
                    value: "{{ $branch->id }}"
                },
            @endforeach
        ];

        const serviceDeptAdminBranchSelect = document.querySelector('#select-service-dept-admin-branch');
        VirtualSelect.init({
            ele: serviceDeptAdminBranchSelect,
            options: serviceDeptAdminBranchOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
        });

        serviceDeptAdminBranchSelect.addEventListener('change', () => {
            const branchIds = serviceDeptAdminBranchSelect.value;
            @this.set('branches', branchIds);
        });

        serviceDeptAdminBranchSelect.addEventListener('reset', () => {
            serviceDeptAdminBUDepartmentSelect.reset();
        });

        const serviceDeptAdminBUDepartmentOption = [
            @foreach ($buDepartments as $buDepartment)
                {
                    label: "{{ $buDepartment->name }}",
                    value: "{{ $buDepartment->id }}"
                },
            @endforeach
        ];
        const serviceDeptAdminBUDepartmentSelect = document.querySelector('#select-service-dept-admin-bu-department');
        VirtualSelect.init({
            ele: serviceDeptAdminBUDepartmentSelect,
            options: serviceDeptAdminBUDepartmentOption,
            search: true,
            markSearchResults: true,
        });

        serviceDeptAdminBUDepartmentSelect.addEventListener('change', () => {
            @this.set('bu_department', parseInt(serviceDeptAdminBUDepartmentSelect.value));
        });

        const serviceDeptAdminServiceDepartmentOption = [
            @foreach ($serviceDeptAdminServiceDepartments as $serviceDepartment)
                {
                    label: "{{ $serviceDepartment->name }}",
                    value: "{{ $serviceDepartment->id }}"
                },
            @endforeach
        ];

        const serviceDeptAdminSeviceDepartmentSelect = document.querySelector(
            '#select-service-dept-admin-service-department');
        VirtualSelect.init({
            ele: serviceDeptAdminSeviceDepartmentSelect,
            options: serviceDeptAdminServiceDepartmentOption,
            search: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
        });

        serviceDeptAdminSeviceDepartmentSelect.addEventListener('change', () => {
            @this.set('service_departments', serviceDeptAdminSeviceDepartmentSelect.value);
        });
    </script>
@endpush

@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addNewServiceDeptAdminModal').modal('hide');
            serviceDeptAdminSuffixSelect.reset();
            serviceDeptAdminBranchSelect.reset();
            agentTeamSelect.reset();
            serviceDeptAdminBUDepartmentSelect.reset();
            serviceDeptAdminSeviceDepartmentSelect.reset();
        });
    </script>
@endpush
