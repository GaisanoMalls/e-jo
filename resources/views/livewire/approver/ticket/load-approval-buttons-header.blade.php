<div>
    {{-- @if ($ticket->status_id === App\Models\Status::OPEN || $ticket->approval_status ===
    App\Models\ApprovalStatus::FOR_APPROVAL) --}}
    @if (optional($ticket->helpTopic)->specialProject)
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
        <button type="button" class="btn btn-sm btn__disapprove__ticket" data-bs-toggle="modal"
            data-bs-target="#disapproveTicketModal" type="button">
            Disapprove
        </button>
        <button type="button" class="btn btn-sm shadow btn__approve__ticket" data-bs-toggle="modal"
            data-bs-target="#confirmTicketApproveModal">
            Approve
        </button>
    </div>
    @endif
    {{-- @endif --}}
</div>