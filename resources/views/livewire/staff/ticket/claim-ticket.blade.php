@php
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

@if (
    ($ticket->status_id != Status::CLOSED && $ticket->approval_status == ApprovalStatusEnum::APPROVED) ||
        $this->isDoneFirstLevelApproval())
    <div class="d-flex flex-column">
        @if ($ticket->status_id == Status::CLAIMED)
            <button style="background-color: {{ $ticket->status->color }} !important;"
                class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex  align-items-center justify-content-center">
                <i class="fa-regular fa-flag"></i>
            </button>
            <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
        @else
            @if (is_null($ticket->agent_id))
                <button type="submit"
                    class="btn bx-burst btn-sm border-0 m-auto ticket__detatails__btn__claim d-flex align-items-center justify-content-center"
                    wire:click="claimTicket">
                    <i wire:loading.remove class="fa-regular fa-flag"></i>
                    <div wire:loading wire:target="claimTicket">
                        <div class="d-flex align-items-center gap-2">
                            <div class="spinner-border text-success" style="height: 15px; width: 15px;" role="status">
                            </div>
                        </div>
                    </div>
                </button>
                <small class="ticket__details__topbuttons__label">Claim</small>
            @else
                <button style="background-color: #FF8B8B !important;"
                    class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex  align-items-center justify-content-center">
                    <i class="fa-regular fa-flag"></i>
                </button>
                <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
            @endif
        @endif
    </div>
@elseif ($ticket->status_id == Status::CLAIMED)
    <div class="d-flex flex-column">
        <button style="background-color: {{ $ticket->status->color }} !important;"
            class="btn btn-sm border-0 m-auto text-white ticket__detatails__btn__claim claimed d-flex align-items-center justify-content-center">
            <i class="fa-regular fa-flag"></i>
        </button>
        <small class="ticket__details__topbuttons__label fw-bold">Claimed</small>
    </div>
@endif
