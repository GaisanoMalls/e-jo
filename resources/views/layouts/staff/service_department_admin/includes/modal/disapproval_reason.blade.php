<div class="modal slideIn animate disapprove__reason__modal" id="disapproveTicketModal" tabindex="-1"
    aria-labelledby="disapproveTicketLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 modal__content">
            <form action="{{ route('approver.ticket.disapprove_ticket', $ticket->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body border-0 px-4 pt-4 pb-1 mb-3">
                    <h6 class="mb-3 title">
                        Reason why you disapprove this ticket.
                    </h6>
                    <textarea id="myeditorinstance" name="description" placeholder="Type here..."></textarea>
                    @error('description', 'disapproveTicket')
                    <span class="error__message">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="d-flex align-items-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-auto btn__cancel__logout btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn w-auto btn__confirm__logout btn__confirm__modal">Disapprove</button>
                </div>
            </form>
        </div>
    </div>
</div>