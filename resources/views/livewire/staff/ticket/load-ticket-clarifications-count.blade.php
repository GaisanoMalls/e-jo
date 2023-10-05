<div>
    <div class="my-2">
        <small class="ticket__discussions text-muted">
            {{ $ticket->clarifications->count() > 1 ? 'Clarifications' : 'Clarification' }}
            ({{ $ticket->clarifications->count() }})
        </small>
    </div>
</div>