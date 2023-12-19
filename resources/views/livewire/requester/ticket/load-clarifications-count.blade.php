<div>
    {{ $ticket->clarifications->count() > 1 ? 'Clarifications' : 'Clarification' }}
    ({{ $ticket->clarifications->count() }})
</div>
