@if (auth()->user()->role_id === App\Models\Role::SERVICE_DEPARTMENT_ADMIN)
<div>
    <div class="btn-group">
        <div class="d-flex flex-column">
            <button type="button"
                class="btn btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-handshake"></i>
            </button>
            <small class="ticket__details__topbuttons__label">Approval</small>
            <ul class="dropdown-menu dropdown-menu-end approval__dropdown__menu slideIn animate">
                <li>
                    <button type="button"
                        class="btn d-flex align-items-center gap-2 w-100 dropdown__select__button button__approve"
                        data-bs-toggle="modal" data-bs-target="#confirmTicketApproveModal" {{ $ticket->status_id ===
                        App\Models\Status::APPROVED ? 'disabled' : '' }}>
                        <i class="bi bi-check-lg"></i>
                        Approve
                    </button>
                </li>
                <li>
                    <button type="button"
                        class="btn d-flex align-items-center gap-2 w-100 dropdown__select__button button__disapprove"
                        data-bs-toggle="modal" data-bs-target="#confirmTicketDisapproveModal" {{ $ticket->status_id ===
                        App\Models\Status::DISAPPROVED ? 'disabled' : '' }}>
                        <i class="bi bi-x-lg"></i>
                        Disapprove
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>
@endif