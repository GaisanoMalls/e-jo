<div>
    <div class="d-flex align-items-center gap-2">
        <div wire:loading class="spinner-border spinner-border-sm loading__spinner" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mb-0 ticket__details__status" style="background-color: {{ $ticket->status->color }};">
            {{ $ticket->status->name }}
        </p>
    </div>
</div>
