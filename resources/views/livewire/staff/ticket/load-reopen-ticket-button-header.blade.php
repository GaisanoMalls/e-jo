@php
    use App\Models\Status;
@endphp

@if ($ticket->status_id === Status::OVERDUE || $ticket->status_id === Status::CLOSED)
    <div>
        <div class="d-flex flex-column">
            <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#confirmReopenTicketModal" type="submit">
                <i class="fa-solid fa-check"></i>
            </button>
            <small class="ticket__details__topbuttons__label">Reopen</small>
        </div>
    </div>
@endif
