<div>
    @if ($ticket->status_id != App\Models\Status::CLOSED && $ticket->status_id != App\Models\Status::DISAPPROVED)
        <div class="d-flex flex-column">
            <button type="submit"
                class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#sendTicketClarificationModal" wire:click="getLatestClarification">
                <i class="fa-regular fa-pen-to-square"></i>
            </button>
            <small class="ticket__details__topbuttons__label">Clarify</small>
        </div>
    @endif
</div>
