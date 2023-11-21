@if (auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN) && $ticket->status_id !==
App\Models\Status::DISAPPROVED)
<div>
    @if ($ticket->status_id == App\Models\Status::CLOSED)
    <div class="d-flex flex-column">
        <button class="btn btn-sm border-0 m-auto ticket__detatails__btn__close closed d-flex text-white
                                    align-items-center justify-content-center"
            style="background-color: {{ $ticket->status->color }} !important;">
            <i class="fa-solid fa-check"></i>
        </button>
        <small class="ticket__details__topbuttons__label fw-bold">Closed</small>
    </div>
    @else
    <div class="d-flex flex-column">
        <button
            class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
            data-bs-toggle="modal" data-bs-target="#closeTicketModal" type="submit">
            <i class="fa-solid fa-check"></i>
        </button>
        <small class="ticket__details__topbuttons__label">Close</small>
    </div>
    @endif
</div>
@endif

@push('livewire-modal')
<script>
    window.addEventListener('close-modal', () => {
        $('#closeTicketModal').modal('hide');
    });
</script>
@endpush