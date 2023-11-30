<div>
    @if (!$approvers->isEmpty())
    <div
        class="card account__type__card {{ Route::is('staff.manage.user_account.approvers') ? 'card__rounded__and__no__border' : '' }}">
        <div class="table-responsive custom__table">
            <table class="table table-striped mb-0" @if (Route::is('staff.manage.user_account.approvers')) id="table"
                @endif>
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;;">Name
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Branch
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            BU/Department
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Status
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Permissions
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date
                            Added
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Last
                            Active
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvers as $approver)
                    <tr>
                        <td>
                            <a href="{{ route('staff.manage.user_account.approver.view_details', $approver->id) }}">
                                <div class="media d-flex align-items-center user__account__media">
                                    <div class="flex-shrink-0">
                                        @if ($approver->profile->picture)
                                        <img src="{{ Storage::url($approver->profile->picture) }}" alt=""
                                            class="image-fluid user__picture">
                                        @else
                                        <div class="user__name__initial" style="background-color: #3B4053;">
                                            {{ $approver->profile->getNameInitial() }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column gap-1 ms-3 w-100">
                                        <span class="user__name">{{
                                            $approver->profile->getFullName() }}</span>
                                        <small>{{ $approver->email }}</small>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->getBranches() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->getBUDepartments() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->isActive() ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start gap-1 td__content">
                                <span><i class="bi bi-person-lock text-muted"></i></span>
                                <span>{{ $approver->getAllPermissions()->count() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->dateCreated() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>
                                    @if ($approver->dateUpdated() >
                                    $approver->profile->dateUpdated())
                                    {{ $approver->dateUpdated() }}
                                    @else
                                    {{ $approver->profile->dateUpdated() }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px"
                                    onclick="window.location.href='{{ route('staff.manage.user_account.approver.edit_details', $approver->id) }}'"
                                    type="button" class="btn action__button">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button data-tooltip="Delete" data-tooltip-position="top" data-tooltip-font-size="11px"
                                    type="button" class="btn action__button" data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteApproverModal"
                                    wire:click="deleteApprover({{ $approver->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert text-center d-flex align-items-center justify-content-center gap-2" role="alert"
        style="background-color: #F5F7F9; font-size: 14px;">
        <i class="fa-solid fa-circle-info"></i>
        Empty records for approvers.
    </div>
    @endif

    {{-- Assign Permission --}}
    <div wire:ignore.self class="modal fade assign__user__permission__modal" id="assignApproverPermissionModal"
        tabindex="-1" aria-labelledby="assignApproverPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewTagModalLabel">Assign Permission</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <h6 class="mb-0 mt-3">Approver: {{ $approverFullName }}</h6>
                <form wire:submit.prevent="assignApproverPermission">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div>
                                    <div id="select-assign-approver-permission" placeholder="Select permission"
                                        wire:ignore>
                                    </div>
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
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="assignApproverPermission"
                                    class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                </span>
                                Assign
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Approver Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__user__account" id="confirmDeleteApproverModal"
        tabindex="-1" aria-labelledby="confirmDeleteApproverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h5 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h5>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to delete this approver?
                        </p>
                        <strong>{{ $approverFullName }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn w-50 d-flex align-items-center justify-content-center gap-2 btn__confirm__delete btn__confirm__modal">
                            <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-select')
<script>
    const permissionOption = [
        @foreach ($allPermissions as $permission)
        {
            label: "{{ $permission->name }}",
            value: "{{ $permission->name }}"
        },
        @endforeach
    ];

    const selectApproverPermission = document.querySelector('#select-assign-approver-permission');
    VirtualSelect.init({
        ele: selectApproverPermission,
        options: permissionOption,
        search: true,
        required: true,
        multiple: true,
        showValueAsTags: true,
        markSearchResults: true,
        popupDropboxBreakpoint: '3000px',
    });

    window.addEventListener('refresh-approver-permission-select', (event) => {
        const refreshPermissionOption = [];
        const refreshPermissions = event.detail.allPermissions;

        refreshPermissions.forEach((permission) => {
            refreshPermissionOption.push({
                label: permission.name,
                value: permission.name
            });
        });

        selectApproverPermission.setOptions(refreshPermissionOption);
    });

    selectApproverPermission.addEventListener('change', () => {
        @this.set('approverPermissions', selectApproverPermission.value);
    });
</script>
@endpush

@push('livewire-modal')
<script>
    window.addEventListener('close-modal', () => {
        $('#assignApproverPermissionModal').modal('hide');
    });
</script>
@endpush