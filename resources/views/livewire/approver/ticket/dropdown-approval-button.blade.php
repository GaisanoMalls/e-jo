@php
    use App\Enums\ApprovalStatusEnum;
@endphp

<div>
    <div class="btn-group">
        <div class="d-flex flex-column">
            @if ($ticket->approval_status === ApprovalStatusEnum::APPROVED)
                <button type="button"
                    class="btn btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="background-color: #FF8B8B !important; color: white !important">
                    <i class="bi bi-check-lg"></i>
                </button>
            @elseif ($ticket->approval_status === ApprovalStatusEnum::DISAPPROVED)
                <button type="button"
                    class="btn btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="background-color: #FF8B8B !important; color: white !important">
                    <i class="bi bi-x-lg"></i>
                </button>
            @else
                <button type="button"
                    class="btn btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                    data-bs-toggle="dropdown" aria-expanded="false" @disabled(!$isApproverIsInConfiguration)>
                    <i class="fa-regular fa-handshake"></i>
                </button>
            @endif

            @if ($ticket->approval_status === ApprovalStatusEnum::APPROVED)
                <small class="ticket__details__topbuttons__label">Approved</small>
            @elseif ($ticket->approval_status === ApprovalStatusEnum::DISAPPROVED)
                <small class="ticket__details__topbuttons__label">Disapproved</small>
            @else
                <small class="ticket__details__topbuttons__label" @style(['color: rgb(153, 153, 153)' => !$isApproverIsInConfiguration])>Approval</small>
            @endif

            @if (
                $ticket->approval_status !== ApprovalStatusEnum::APPROVED &&
                    $ticket->approval_status !== ApprovalStatusEnum::DISAPPROVED &&
                    $isApproverIsInConfiguration)
                <ul class="dropdown-menu dropdown-menu-end approval__dropdown__menu slideIn animate">
                    <li>
                        <button type="button"
                            class="btn d-flex align-items-center gap-2 w-100 dropdown__select__button button__approve"
                            data-bs-toggle="modal" data-bs-target="#confirmTicketApproveModal">
                            <i class="bi bi-check2-circle"></i>
                            Approve
                        </button>
                    </li>
                    <li>
                        <button type="button"
                            class="btn d-flex align-items-center gap-2 w-100 dropdown__select__button button__disapprove"
                            data-bs-toggle="modal" data-bs-target="#confirmTicketDisapproveModal">
                            <i class="bi bi-x-lg"></i>
                            Disapprove
                        </button>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>
