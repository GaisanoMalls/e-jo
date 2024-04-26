<div>
    <button wire:click="generatePermissions" wire:loading.attr="disabled" type="button"
        class="btn d-flex align-items-center justify-content-center gap-2"
        style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; border: 1px solid #bdbcbd; color: #3e3d3d; font-weight: 500;">
        <span wire:loading wire:target="generatePermissions" class="spinner-border spinner-border-sm" role="status"
            aria-hidden="true">
        </span>
        <span wire:loading.remove wire:target="generatePermissions" class="button__name">Generate</span>
        <span wire:loading wire:target="generatePermissions" class="button__name">Generating...</span>
    </button>
</div>
