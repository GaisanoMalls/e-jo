<div>
    @if (!is_null($ticket->agent_id))
        @if ($ticket->has('ticketCosting') && is_null($ticket->ticketCosting?->amount))
            <div class="d-flex flex-column">
                <button type="button"
                    class="btn btn-sm border-0 m-auto ticket__detatails__btn__costing d-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#addCostingModal">
                    ₱
                </button>
                <small class="ticket__details__topbuttons__label">Costing</small>
            </div>
        @endif
    @endif
</div>
