<div>
    <div class="roles__permissions__type__card">
        <div class="table-responsive custom__table">
            @if (!$roles->isEmpty())
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Role
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Permissions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start gap-1 td__content">
                                        @if ($role->permissions()->count() !== 0)
                                            @foreach ($role->permissions->map->only('id', 'name') as $permission)
                                                <span
                                                    class="rounded-4 ps-2 pe-1 d-flex align-items-center gap-1 role__permission">
                                                    {{ $permission['name'] }}
                                                    <i class="bi bi-x remove__permission__icon"
                                                        wire:click="removePermission({{ $role->id }}, {{ $permission['id'] }})"></i>
                                                </span>
                                            @endforeach
                                        @else
                                            <span>----</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                        <button data-tooltip="Assign Permission" data-tooltip-position="top"
                                            data-tooltip-font-size="11px" type="button" class="btn action__button"
                                            data-bs-toggle="modal" data-bs-target="#assignPermissionToRoleModal"
                                            wire:click="assignPermissionToRole({{ $role->id }})">
                                            <i class="bi bi-person-lock"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">Empty</small>
                </div>
            @endif
        </div>
    </div>

    {{-- Add role permissions modal --}}
    <div wire:ignore.self class="modal fade assign__permission__to__role__modal" id="assignPermissionToRoleModal"
        tabindex="-1" aria-labelledby="assignPermissionToRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewTagModalLabel">Assign Permission</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <h6 class="mb-0 mt-3">Role: {{ $roleName }}</h6>
                <form wire:submit.prevent="givePermission">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div>
                                    <div id="select-assign-permission" placeholder="Select permission" wire:ignore>
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
                                <span wire:loading wire:target="givePermission" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Assign
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

        const selectPermission = document.querySelector('#select-assign-permission');
        VirtualSelect.init({
            ele: selectPermission,
            options: permissionOption,
            search: true,
            required: true,
            multiple: true,
            showValueAsTags: true,
            markSearchResults: true,
            popupDropboxBreakpoint: '3000px',
        });

        window.addEventListener('refresh-permission-select', (event) => {
            const refreshPermissionOption = [];
            const refreshPermissions = event.detail.allPermissions;
            const currentPermissions = event.detail.currentPermissions;

            refreshPermissions.forEach((permission) => {
                refreshPermissionOption.push({
                    label: permission.name,
                    value: permission.name
                });
            });

            selectPermission.setOptions(refreshPermissionOption)
            selectPermission.setValue(currentPermissions)
        });

        // Set value for permissions
        selectPermission.addEventListener('change', () => {
            @this.set('permissions', selectPermission.value);
        });
    </script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#assignPermissionToRoleModal').modal('hide');
            selectPermission.reset();
        });
    </script>
@endpush
