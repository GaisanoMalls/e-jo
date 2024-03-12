<div wire:poll.visible.5s>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Level of Approval</label>
            <div class="d-flex flex-column level__approval__container">
                <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                    <small class="level__number__label">
                        Level 1 {{ $this->getLevel1Approvers()->count() > 1 ? 'Approvers' : 'Approver' }}
                        ({{ $this->isTicketApproval1Level1Approved() && !$this->isTicketApproval2Level1Approved() ? '1' : ($this->isTicketApproval2Level1Approved() ? '2' : '0') }}/2)
                    </small>
                    @if ($this->isTicketApproval1Level1Approved())
                        <div class="d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle-fill ms-1" style="font-size: 11px; color: #D32839;"></i>
                            <div class="border-0 text-muted" style="font-size: 0.75rem;">
                                Approved
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-custom-class="custom-tooltip" data-bs-title="To be approved by level 1 approver">
                            <i class="bi bi-info-circle-fill ms-1" style="font-size: 11px; color: #D32839;"></i>
                            <div class="border-0 text-muted" style="font-size: 0.75rem;">
                                Pending
                            </div>
                        </div>
                    @endif
                </div>
                @foreach ($this->getLevel1Approvers() as $level1Approver)
                    <div class="d-flex flex-column">
                        <div
                            class="d-flex align-items-center justify-content-between ps-3 position-relative level__approver__container">
                            <div class="level__approval__approver__dot position-absolute rounded-circle"></div>
                            <div class="d-flex align-items-center {{ !$this->isTicketApproval1Level1Approved() ? 'text-muted' : '' }}"
                                style="padding: 4px 0 4px 0;">
                                @if ($level1Approver->profile->picture)
                                    <img src="{{ Storage::url($level1Approver->profile->picture) }}" alt=""
                                        class="image-fluid level__approval__approver__picture">
                                @else
                                    <div class="level__approval__approver__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                        text-white"
                                        style="background-color: #9DA85C;">
                                        {{ $level1Approver->profile->getNameInitial() }}
                                    </div>
                                @endif
                                <small class="approver__name">
                                    {{ $level1Approver->profile->getFullName() }}
                                    @if ($level1Approver->id == auth()->user()->id)
                                        <span class="text-muted">(You)</span>
                                    @endif
                                    @if ($this->ticketLevel1ApprovalApprovedBy() == $level1Approver->id)
                                        <i class="bi bi-check-lg text-muted"></i>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex flex-column level__approval__container text-muted">
                <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                    <small class="level__number__label">
                        Level 2 {{ $this->getLevel2Approvers()->count() > 1 ? 'Approvers' : 'Approver' }}
                        ({{ $this->isTicketApproval1Level2Approved() && !$this->isTicketApproval2Level2Approved() ? '1' : ($this->isTicketApproval2Level2Approved() ? '2' : '0') }}/2)
                    </small>
                    @if (
                        $this->isTicketApproval1Level1Approved() &&
                            $this->isTicketApproval1Level2Approved() &&
                            !$this->isTicketApproval2Level2Approved())
                        @if ($this->isOnlyApproverForLastApproval())
                            <button wire:click="approveApproval2Level2Approver" wire:loading.class="disabled"
                                class="btn btn-sm d-flex align-items-center gap-2 btn__approve" type="button">
                                <div wire:loading wire:target="approveApproval2Level2Approver"
                                    class="spinner-border spinner-border-sm loading__spinner" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                Approve
                            </button>
                        @else
                            @if ($this->isTicketApproval1Level1Approved() || $this->isTicketApproval1Level2Approved())
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill ms-1"
                                        style="font-size: 11px; color: #D32839;"></i>
                                    <div class="border-0 text-muted" style="font-size: 0.75rem;">
                                        Approved
                                    </div>
                                </div>
                            @endif
                        @endif
                    @else
                        @if ($this->isTicketApproval2Level2Approved())
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill ms-1" style="font-size: 11px; color: #D32839;"></i>
                                <div class="border-0 text-muted" style="font-size: 0.75rem;">
                                    Approved
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                @foreach ($this->getLevel2Approvers() as $level2Approver)
                    <div class="d-flex flex-column">
                        <div
                            class="d-flex align-items-center justify-content-between ps-3 position-relative level__approver__container">
                            <div class="level__approval__approver__dot position-absolute rounded-circle"></div>
                            <div class="d-flex align-items-center" style="padding: 4px 0 4px 0;">
                                @if ($level2Approver->profile->picture)
                                    <img src="{{ Storage::url($level2Approver->profile->picture) }}" alt=""
                                        class="image-fluid level__approval__approver__picture">
                                @else
                                    <div class="level__approval__approver__name__initial d-flex align-items-center p-2 me-2 justify-content-center
                                        text-white"
                                        style="background-color: #3B4053;">
                                        {{ $level2Approver->profile->getNameInitial() }}
                                    </div>
                                @endif
                                <small
                                    class="approver__name {{ $this->isTicketApproval1Level2Approved() ? 'text-dark' : '' }}">
                                    {{ $level2Approver->profile->getFullName() }}
                                    @if ($level2Approver->id == auth()->user()->id)
                                        <span class="text-muted">(You)</span>
                                    @endif
                                    @if ($this->ticketLevel2ApprovalApprovedBy() == $level2Approver->id)
                                        <i class="bi bi-check-lg text-muted"></i>
                                    @endif
                                </small>
                            </div>
                            @if ($level2Approver->id == auth()->user()->id)
                                @if (!$this->isTicketApproval1Level2Approved() && $this->isTicketApproval1Level1Approved())
                                    <button wire:click="approveTicketApproval1level2Approver"
                                        wire:loading.class="disabled"
                                        class="btn btn-sm d-flex align-items-center gap-2 btn__approve" type="button">
                                        <div wire:loading wire:target="approveTicketApproval1level2Approver"
                                            class="spinner-border spinner-border-sm loading__spinner" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        Approve
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
