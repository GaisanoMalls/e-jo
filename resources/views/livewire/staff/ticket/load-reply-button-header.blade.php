@php
    use App\Models\Role;
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

@if ($ticket->status_id != Status::CLOSED && $ticket->status_id != Status::DISAPPROVED)
    <div>
        <div class="d-flex flex-column">
            @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) && $ticket->approval_status === ApprovalStatusEnum::FOR_APPROVAL)
                <button type="button"
                    class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                    {{ $ticket->approval_status === ApprovalStatusEnum::FOR_APPROVAL ? 'disabled' : '' }}>
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
            @else
                <button type="button"
                    class="btn btn-sm border-0 m-auto ticket__detatails__btn__close d-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#replyTicketModal" wire:click="getLatestReply">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
            @endif
            <small
                class="ticket__details__topbuttons__label {{ auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) && $ticket->approval_status === ApprovalStatusEnum::FOR_APPROVAL? 'text-muted': '' }}">Reply</small>
        </div>
    </div>
@endif
