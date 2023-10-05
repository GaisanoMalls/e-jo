<div>
    <div class="my-2">
        <small class="ticket__discussions text-muted">
            {{ $ticket->replies->count() > 1 ? 'Discussions' : 'Discussion' }}
            ({{ $ticket->replies->count() }})
        </small>
    </div>
</div>