<div>
    <div>
        <button wire:click="generateDefaultRolePermissions" wire:loading.attr="disabled" type="button"
            class="btn d-flex align-items-center justify-content-center gap-2"
            style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; border: 1px solid rgb(223, 228, 233); color: #3e3d3d; font-weight: 500; background-color: #f3f4f6;">
            <span wire:loading wire:target="generateDefaultRolePermissions" class="spinner-border spinner-border-sm"
                role="status" aria-hidden="true">
            </span>
            <span wire:loading.remove wire:target="generateDefaultRolePermissions" class="button__name"
                style="white-space: nowrap;">Set default
                permissions</span>
            <span wire:loading wire:target="generateDefaultRolePermissions" class="button__name"
                style="white-space: nowrap;">Setting default permissions...</span>
        </button>
    </div>
</div>
