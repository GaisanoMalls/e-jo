@php
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

<div>
    @switch($ticket->status_id)
        @case(Status::OPEN)
            <a href="{{ route('approver.tickets.open') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::VIEWED)
            <a href="{{ route('approver.tickets.viewed') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::APPROVED)
            <a href="{{ route('approver.tickets.approved') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break

        @case(Status::ON_PROCESS)
            <a href="{{ route('approver.tickets.on_process') }}" type="button"
                class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        @break
    @endswitch
    @if ($ticket->approval_status === ApprovalStatusEnum::DISAPPROVED)
        <a href="{{ route('approver.tickets.disapproved') }}" type="button"
            class="btn btn-sm rounded-circle text-muted d-flex align-items-center justify-content-center text-center btn__back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    @endif
</div>
