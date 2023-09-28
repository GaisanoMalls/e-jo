<div>
    {{-- Close Ticket Modal --}}
    <div wire:ignore.self class="modal slideIn animate modal__confirm__close__ticket" id="closeTicketModal"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="closeTicket">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h6 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h6>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to close this ticket?
                        </p>
                        <strong>{{ $ticket->ticket_number }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal">
                            <span wire:loading wire:target="closeTicket" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Yes, close this ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>