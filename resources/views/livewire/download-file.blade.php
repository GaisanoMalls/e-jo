<div>
    <button wire:click="downloadFile('{{ $filePath }}')" wire:loading.attr="disabled" type="button"
        class="btn btn-sm d-flex align-items-center justify-content-center btn-outline-secondary gap-2">
        <i wire:loading.remove wire:target="downloadFile" class="fa-solid fa-download"></i>
        <span wire:loading wire:target="downloadFile" class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
        </span>
        <span wire:loading.remove wire:target="downloadFile">Download</span>
        <span wire:loading wire:target="downloadFile">Downloading...</span>
    </button>
</div>
