<div>
    @livewire('staff.accounts.approver.update-approver-password', ['approver' => $approver])
    <div class="row justify-content-center accounts__section">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 users__account__card">
                <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Approver's Information</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated:
                        @if ($approver->dateUpdated() > $approver->profile->dateUpdated())
                        {{ $approver->dateUpdated() }}
                        @else
                        {{ $approver->profile->dateUpdated() }}
                        @endif
                    </small>
                </div>
                <form wire:submit.prevent="updateApproverAccount">
                    <input type="hidden" id="approverUserID" value="{{ $approver->id }}">
                    <div class="row gap-4 user__details__container">
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label form__field__label">First
                                            name</label>
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
                                        <label for="middle_name" class="form-label form__field__label">Middle
                                            name</label>
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
                                <div class="col-md-6">
                                    <div class="mb-3">
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
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Assigned Permissions</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Permissions</label>
                                        <div>
                                            <div id="select-approver-permissions" wire:ignore></div>
                                        </div>
                                        @error('permissions')
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
                                    onclick="window.location.href='{{ route('staff.manage.user_account.approvers') }}'">Cancel</button>
                                <button type="submit"
                                    class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__details btn__send">
                                    <span wire:loading wire:target="updateApproverAccount"
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
        selectedValue: '{{ $approver->profile->suffix }}'
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
        selectedValue: @json($branches)
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
        selectedValue: @json($bu_departments)
    });
    approverBUDepartmentSelect.addEventListener('change', () => {
        @this.set('bu_departments', approverBUDepartmentSelect.value);
    });

    const approverPermissionOption = [
        @foreach ($allPermissions as $permission)
        {
            label: "{{ $permission->name }}",
            value: "{{ $permission->name }}"
        },
        @endforeach
    ];

    const approverPermissionSelect = document.querySelector('#select-approver-permissions');
    VirtualSelect.init({
        ele: approverPermissionSelect,
        options: approverPermissionOption,
        search: true,
        multiple: true,
        showValueAsTags: true,
        markSearchResults: true,
        selectedValue: @json($currentPermissions)
    });

    approverPermissionSelect.addEventListener('change', () => {
        @this.set('permissions', approverPermissionSelect.value);
    });

    approverPermissionSelect.addEventListener('reset', () => {
        @this.set('permissions', []);
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