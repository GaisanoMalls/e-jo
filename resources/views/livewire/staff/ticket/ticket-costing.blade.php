<div>
    @if (!is_null($ticket->ticketCosting))
        <div class="row p-4 ticket__costing__container">
            <div class="col-md-8">
                <div class="d-flex gap-2 flex-wrap justify-content-between">
                    <div class="d-flex flex-column justify-content-between gap-2">
                        <small class="text-muted text-sm">Amount set</small>
                        <div class="d-flex gap-1">
                            <span class="currency text-muted">₱</span>
                            <span class="amount">
                                {{ $ticket->ticketCosting?->amount }}
                            </span>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-between gap-2">
                        <small class="text-muted text-sm">Attachment/s</small>
                        <small class="mb-2">Image</small>
                    </div>
                    <div class="d-flex flex-column justify-content-between gap-2">
                        <small class="text-muted text-sm">Action</small>
                        <div class="d-flex gap-1">
                            <small class="mb-2">Edit</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
