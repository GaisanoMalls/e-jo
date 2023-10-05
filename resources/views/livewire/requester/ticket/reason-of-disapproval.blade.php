<div>
    @if ($ticket->approval_status === App\Models\ApprovalStatus::DISAPPROVED)
    <div class="alert alert-warning p-3 rounded-3 border-0 mt-4 mb-3" role="alert" style="font-size: 13px;">
        <div class="mb-2">We regret to inform you that the approver has disapproved your ticket. After careful
            consideration, the
            decision has been made not to proceed with the requested action at this time.
            <br>
            Please feel free to reach out
            to the approver or the relevant team if you have any questions or require further
            clarification on the disapproval decision. They will be more than willing to assist you with any concerns
            you may have.
        </div>
        @if ($reason)
        <button type="button" class="btn btn-sm p-0 d-flex align-items-center rounded-0 border-0 gap-1 btn__see__reason"
            data-bs-toggle="modal" data-bs-target="#reasonModal">
            See reason of disapproval
        </button>
        @endif
    </div>
    @endif

    @if ($reason)
    <div class="modal fade reason__modal" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 modal__content">
                <div class="modal-header p-0 border-0">
                    <h6 class="title mb-0">Reason of Disapproval</h6>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="reason__content mt-3 py-2 px-3 bg-light rounded-3">
                    {!! $reason->description !!}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>