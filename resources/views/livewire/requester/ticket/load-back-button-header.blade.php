@php
    use App\Models\Status;
@endphp

<div>
    @switch($ticket->status_id)
        @case(Status::OPEN)
            <a href="{{ route('user.tickets.open_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::ON_PROCESS)
            <a href="{{ route('user.tickets.on_process_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::VIEWED)
            <a href="{{ route('user.tickets.viewed_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::APPROVED)
            <a href="{{ route('user.tickets.approved_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::DISAPPROVED)
            <a href="{{ route('user.tickets.disapproved_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::CLOSED)
            <a href="{{ route('user.tickets.closed_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break
    @endswitch
</div>
