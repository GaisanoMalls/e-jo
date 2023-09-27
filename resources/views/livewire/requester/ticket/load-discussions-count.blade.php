<div>
    {{ $ticket->replies->count() > 1 ? 'Discussions' : 'Discussion' }}
    ({{ $ticket->replies->count() }})
</div>