<div>
    <form wire:submit.prevent="savePermission">
        <div class="row mx-4 pt-3 gap-3">
            <div class="col-1 mt-2">
                <span style="font-size: 14px;">Can</span>
            </div>
            <div class="col-md-3 p-0">
                <div>
                    <div id="select-permission-action" placeholder="Select action" wire:ignore></div>
                </div>
                @error('permissionAction')
                <span class="error__message">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="col-md-5 p-0">
                <div>
                    <div id="select-module-name" placeholder="Select module" wire:ignore></div>
                </div>
                @error('permissionModules')
                <span class="error__message">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="col-md-2 p-0">
                <button type="submit" class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                    style="padding-top: 20px; padding-bottom: 20px;">
                    <i class="fa-solid fa-plus" wire:loading.class="d-none" wire:target="savePermission"></i>
                    <span wire:loading wire:target="savePermission" class="spinner-border spinner-border-sm"
                        role="status" aria-hidden="true">
                    </span>
                    <span class="button__name">Add</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('livewire-select')
<script>
    const actions = @json($actions);
    console.log(actions);
    const actionOptions = [
        @foreach ($actions as $action)
        {
            label: @json($action->icon) + "{{ $action->name }}",
            value: "{{ $action->name }}"
        },
        @endforeach

    ];

    const selectPermissionAction = document.querySelector('#select-permission-action');
    VirtualSelect.init({
        ele: selectPermissionAction,
        options: actionOptions,
        search: true,
        required: true,
        markSearchResults: true,
    });

    const moduleOption = [
        @foreach ($modules as $module)
        {
            label: "{{ $module->name }}",
            value: "{{ $module->name }}"
        },
        @endforeach
    ];

    const selectPermissionModule = document.querySelector('#select-module-name');
    VirtualSelect.init({
        ele: selectPermissionModule,
        options: moduleOption,
        search: true,
        required: true,
        multiple: true,
        allowNewOption: true,
        showValueAsTags: true,
        markSearchResults: true,
    });

    // Set Value
    selectPermissionAction.addEventListener('change', () => {
        @this.set('permissionAction', selectPermissionAction.value);
    });

    selectPermissionModule.addEventListener('change', () => {
        @this.set('permissionModules', selectPermissionModule.value);
    });

    // Reset select options
    window.addEventListener('clear-select-options', () => {
        selectPermissionAction.reset();
        selectPermissionModule.reset();
    });
</script>
@endpush