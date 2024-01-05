<div wire:poll.visible.7s>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Level of Approval</label>
            <div class="d-flex flex-column level__approval__container">
                <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                    <small class="level__number__label">
                        Level 1 {{ $level1Approvers->count() > 1 ? 'Approvers' : 'Approver' }}
                    </small>
                    @if ($isTicketLevel1Approved)
                        <div class="d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle-fill ms-1" style="font-size: 11px; color: #D32839;"></i>
                            <div class="border-0 text-muted" style="font-size: 0.75rem;">
                                Approved
                            </div>
                        </div>
                    @endif
                </div>
                @foreach ($level1Approvers as $level1Approver)
                    <div class="d-flex flex-column">
                        <div
                            class="d-flex align-items-center justify-content-between ps-3 position-relative level__approver__container">
                            <div class="level__approval__approver__dot position-absolute rounded-circle"></div>
                            <div class="d-flex align-items-center" style="padding: 4px 0 4px 0;">
                                @if ($level1Approver->profile->picture)
                                    <img src="{{ Storage::url($level1Approver->profile->picture) }}" alt=""
                                        class="image-fluid level__approval__approver__picture">
                                @else
                                    <div class="level__approval__approver__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                        text-white"
                                        style="background-color: #3B4053;">
                                        {{ $level1Approver->profile->getNameInitial() }}
                                    </div>
                                @endif
                                <small class="approver__name">
                                    {{ $level1Approver->profile->getFullName() }}
                                    @if ($level1Approver->id == auth()->user()->id)
                                        <span class="text-muted">(You)</span>
                                    @endif
                                    @if ($currentTicketApprover == $level1Approver->id)
                                        <i class="bi bi-check-lg text-muted"></i>
                                    @endif
                                </small>
                            </div>
                            @if (!$isApprovedByLevel2Approver)
                                @if ($level1Approver->id == auth()->user()->id)
                                    @if ($isTicketLevel1Approved)
                                        @if ($currentTicketApprover == auth()->user()->id)
                                            <button wire:click="undoLevel1Approve" wire:loading.class="disabled"
                                                type="button"
                                                class="btn border-0 fw-semibold d-flex align-items-center gap-2"
                                                style="color: #D32839; font-size: 0.75rem;">
                                                <div wire:loading wire:target="undoLevel1Approve"
                                                    class="spinner-border spinner-border-sm loading__spinner"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                Undo
                                            </button>
                                        @endif
                                    @else
                                        @if ($ticket->approval_status == App\Models\ApprovalStatus::APPROVED)
                                            <button wire:click="level1Approve" wire:loading.class="disabled"
                                                class="btn btn-sm d-flex align-items-center gap-2 btn__approve"
                                                type="button">
                                                <div wire:loading wire:target="level1Approve"
                                                    class="spinner-border spinner-border-sm loading__spinner"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                Approve
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex flex-column level__approval__container text-muted">
                <div class="d-flex flex-column mb-2">
                    <small class="level__number__label"
                        style="{{ !$isTicketLevel1Approved ? 'color: #adadad !important;' : '' }}">
                        Level 2 {{ $level2Approvers->count() > 1 ? 'Approvers' : 'Approver' }}
                    </small>
                    @if ($isTicketLevel1Approved)
                        <small class="text-muted" style="font-size: 12px;">
                            Ticket approved by Level 1 and is now pending for Level 2 approval. If UNDO is clicked, the
                            approval for Level 2 will be cancelled.
                        </small>
                    @endif
                </div>
                @foreach ($level2Approvers as $level2Approvers)
                    <div class="d-flex flex-column">
                        <div
                            class="d-flex align-items-center justify-content-between ps-3 position-relative level__approver__container">
                            <div class="level__approval__approver__dot position-absolute rounded-circle"></div>
                            <div class="d-flex align-items-center" style="padding: 4px 0 4px 0;">
                                @if ($level2Approvers->profile->picture)
                                    <img src="{{ Storage::url($level2Approvers->profile->picture) }}" alt=""
                                        class="image-fluid level__approval__approver__picture">
                                @else
                                    <div class="level__approval__approver__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                        text-white"
                                        style="background-color: {{ $isTicketLevel1Approved ? '#3B4053' : '#757a8f' }};">
                                        {{ $level2Approvers->profile->getNameInitial() }}
                                    </div>
                                @endif
                                <small class="approver__name {{ $isTicketLevel1Approved ? 'text-dark' : '' }}">
                                    {{ $level2Approvers->profile->getFullName() }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
