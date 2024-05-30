@php
    use App\Models\Role;
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

<div>
    @if ($ticket->status->id == Status::APPROVED)
        @if (auth()->user()->hasRole(Role::AGENT))
            <a href="{{ route('staff.tickets.open_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @else
            <a href="{{ route('staff.tickets.approved_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @endif
    @endif
    @if ($ticket->status->id == Status::OPEN)
        <a href="{{ route('staff.tickets.open_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
    @if ($ticket->status->id == Status::CLAIMED)
        <a href="{{ route('staff.tickets.claimed_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
    @if ($ticket->status->id == Status::ON_PROCESS)
        <a href="{{ route('staff.tickets.on_process_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
    @if ($ticket->status->id == Status::VIEWED)
        <a href="{{ route('staff.tickets.viewed_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
    @if ($ticket->status->id == Status::OVERDUE)
        <a href="{{ route('staff.tickets.overdue_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
    @if ($ticket->status->id == Status::CLOSED && $ticket->approval_status == ApprovalStatusEnum::DISAPPROVED)
        <a href="{{ route('staff.tickets.disapproved_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
    @if (
        $ticket->status->id == Status::CLOSED ||
            ($ticket->approval_status == ApprovalStatusEnum::APPROVED &&
                $ticket->approval_status == ApprovalStatusEnum::DISAPPROVED))
        <a href="{{ route('staff.tickets.closed_tickets') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
</div>
