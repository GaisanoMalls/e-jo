<div>
    @if ($ticket->status_id != App\Models\Status::CLOSED)
    <div class="d-flex flex-column">
        <button type="submit"
            class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
            data-bs-toggle="modal" data-bs-target="#replyTicketModal" wire:click="getLatestReply">
            <i class="fa-regular fa-pen-to-square"></i>
        </button>
        <small class="ticket__details__topbuttons__label">Reply</small>
    </div>
    @endif
</div>