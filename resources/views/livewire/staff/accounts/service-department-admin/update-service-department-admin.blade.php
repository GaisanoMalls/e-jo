<div>
    @livewire('staff.accounts.service-department-admin.update-service-dept-admin-password', ['serviceDeptAdmin' => $serviceDeptAdmin])
    <div class="row accounts__section justify-content-center">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 users__account__card">
                <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Service Dept. Admin's Information</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated:
                        @if ($serviceDeptAdmin->dateUpdated() > $serviceDeptAdmin->profile->dateUpdated())
                            {{ $serviceDeptAdmin->dateUpdated() }}
                        @else
                            {{ $serviceDeptAdmin->profile->dateUpdated() }}
                        @endif
                    </small>
                </div>
                <form wire:submit.prevent="updateServiceDepartmentAdminAccount">
                    <input type="hidden" id="serviceDeptAdminUserID" value="{{ $serviceDeptAdmin->id }}">
                    <div class="row gap-4 user__details__container">
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label form__field__label">
                                            First name
                                        </label>
                                        <input type="text" wire:model="first_name" class="form-control form__field"
                                            id="first_name" placeholder="Enter first name (required)">
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
                                        <label for="middle_name" class="form-label form__field__label">
                                            Middle name
                                        </label>
                                        <input type="text" wire:model="middle_name" class="form-control form__field"
                                            id="middle_name" placeholder="Enter middle name (optional)">
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
                                        <input type="text" wire:model="last_name" class="form-control form__field"
                                            id="last_name" placeholder="Enter last name (required)">
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
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Login Credentials</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label form__field__label">Email</label>
                                        <input type="email" wire:model="email" class="form-control form__field"
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" value="{{ $serviceDeptAdmin->department_id }}"
                                            id="serviceDeptAdminCurrentBUDepartmentId">
                                        <label for="branch" class="form-label form__field__label">
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="branch" class="form-label form__field__label">
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
                                @if ($currentUserAsCostingApprover1)
                                    <div class="col-12 mb-3 d-flex">
                                        <input wire:model="asCostingApprover1"
                                            class="form-check-input check__special__project" type="checkbox"
                                            role="switch" id="checkCostingApprover1" wire:loading.attr="disabled"
                                            style="margin-top: 7px !important;">
                                        <label class="form-check-label" for="checkCostingApprover1">
                                            @if ($asCostingApprover1)
                                                Costing Approver 1
                                            @else
                                                Add as costing approver 1
                                            @endif
                                        </label>
                                    </div>
                                @endif

                                @if ($hasNoCostingApprover1)
                                    <div class="col-12 mb-3 d-flex">
                                        <input wire:model="asCostingApprover1"
                                            class="form-check-input check__special__project" type="checkbox"
                                            role="switch" id="checkCostingApprover1" wire:loading.attr="disabled"
                                            style="margin-top: 7px !important;">
                                        <label class="form-check-label" for="checkCostingApprover1">
                                            Add as costing approver 1
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                    data-bs-dismiss="modal"
                                    onclick="window.location.href='{{ route('staff.manage.user_account.service_department_admins') }}'">Back</button>
                                <button type="submit" class="btn m-0 btn__details btn__send">
                                    <span wire:loading wire:target="updateServiceDepartmentAdminAccount"
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
            selectedValue: '{{ $serviceDeptAdmin->profile->suffix }}'
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
            selectedValue: @json($branches)
        });

        serviceDeptAdminBranchSelect.addEventListener('reset', () => {
            @this.set('branches', null);
            @this.set('bu_department', null);
            serviceDeptAdminBUDepartmentSelect.reset();
            serviceDeptAdminBUDepartmentSelect.disable();
            serviceDeptAdminBUDepartmentSelect.setOptions([]);
        });

        serviceDeptAdminBranchSelect.addEventListener('change', () => {
            const serviceDeptAdminBranchIds = serviceDeptAdminBranchSelect.value;
            if (serviceDeptAdminBranchIds) {
                @this.set('branches', serviceDeptAdminBranchIds);
                serviceDeptAdminBUDepartmentSelect.enable();
                window.addEventListener('get-branch-bu-departments', (event) => {
                    const serviceDeptAdminBUDepartments = event.detail.BUDepartments;
                    const serviceDeptAdminBUDepartmentOption = [];

                    if (serviceDeptAdminBUDepartments.length > 0) {
                        serviceDeptAdminBUDepartments.forEach(function(serviceDeptAdminBUDepartment) {
                            VirtualSelect.init({
                                ele: serviceDeptAdminBUDepartmentSelect
                            });

                            serviceDeptAdminBUDepartmentOption.push({
                                label: serviceDeptAdminBUDepartment.name,
                                value: serviceDeptAdminBUDepartment.id
                            });
                        });
                        serviceDeptAdminBUDepartmentSelect.setOptions(serviceDeptAdminBUDepartmentOption);
                    }
                })
            }
        })

        const serviceDeptAdminBUDepartmentOption = [
            @foreach ($serviceDeptAdminBUDepartments as $department)
                {
                    label: "{{ $department->name }}",
                    value: "{{ $department->id }}"
                },
            @endforeach
        ];

        const serviceDeptAdminBUDepartmentSelect = document.querySelector('#select-service-dept-admin-bu-department');
        VirtualSelect.init({
            ele: serviceDeptAdminBUDepartmentSelect,
            options: serviceDeptAdminBUDepartmentOption,
            search: true,
            markSearchResults: true,
            selectedValue: @json($bu_department)
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
            selectedValue: @json($service_departments)
        });

        serviceDeptAdminSeviceDepartmentSelect.addEventListener('change', () => {
            @this.set('service_departments', serviceDeptAdminSeviceDepartmentSelect.value);
        });
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
