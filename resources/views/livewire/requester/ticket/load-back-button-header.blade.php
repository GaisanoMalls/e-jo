<div>
    @switch($ticket->status_id)
        @case(App\Models\Status::OPEN)
            <a href="{{ route('user.tickets.open_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(App\Models\Status::ON_PROCESS)
            <a href="{{ route('user.tickets.on_process_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(App\Models\Status::VIEWED)
            <a href="{{ route('user.tickets.viewed_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(App\Models\Status::APPROVED)
            <a href="{{ route('user.tickets.approved_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(App\Models\Status::DISAPPROVED)
            <a href="{{ route('user.tickets.disapproved_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(App\Models\Status::CLOSED)
            <a href="{{ route('user.tickets.closed_tickets') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break
    @endswitch
</div>
