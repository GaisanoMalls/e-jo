@php
    use App\Enums\ApprovalStatusEnum;
@endphp

<div>
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
    @if ($ticket->approval_status === ApprovalStatusEnum::DISAPPROVED)
        <div class="alert alert-warning p-3 rounded-3 mx-1 mt-2 mb-4 d-flex align-items-center justify-content-between"
            role="alert" style="font-size: 13px;">
            <span style="font-size: 13px;">
                This ticket has been disapproved.
            </span>
            @if ($reason)
                <button type="button"
                    class="btn btn-sm p-0 d-flex align-items-center border-0 rounded-0 gap-1 btn__see__reason"
                    data-bs-toggle="modal" data-bs-target="#reasonModal">
                    See reason
                </button>
            @endif
        </div>
    @endif
</div>
