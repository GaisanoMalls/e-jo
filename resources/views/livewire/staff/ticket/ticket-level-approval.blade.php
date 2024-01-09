<div>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Level of Approval</label>
            @if ($ticket->helpTopic->specialProject)
                @if (auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                    @if (!$isTicketNeedLevelOfApproval)
                        <div class="d-flex gap-2 border-0 rounded-3"
                            style="color: #664d03; font-size: 13px; background-color: #FFF3CD; padding: 8px 9px; line-height: 18px;">
                            <i class="bi bi-info-circle-fill" style="color: #D32839;"></i>
                            This ticket is associated with a special project, and approval is expected.
                        </div>
                    @endif
                @else
                    <div class="d-flex gap-2 border-0 rounded-3"
                        style="color: #664d03; font-size: 13px; background-color: #FFF3CD; padding: 8px 9px; line-height: 18px;">
                        <i class="bi bi-info-circle-fill" style="color: #D32839;"></i>
                        This ticket is associated with a special project.
                    </div>
                @endif
            @endif
            @if (auth()->user()->hasRole(App\Models\Role::AGENT))
                @if (!is_null($ticket->agent_id))
                    @if (!$isTicketLevel1Approved && !$isTicketLevel2Approved)
                        <div class="form-switch" style="white-space: nowrap;">
                            <input wire:model="isNeedLevelOfApproval" wire:change="toggleAssignLevelOfApproval"
                                class="form-check-input check__need__level__of__approval" type="checkbox" role="switch"
                                id="levelOfApproval" wire:loading.attr="disabled">
                            <label class="form-check-label" for="levelOfApproval">
                                Approval is required at levels 1 and 2
                            </label>
                        </div>
                    @endif
                @else
                    <div class="d-flex gap-2 border-0 rounded-3"
                        style="color: #664d03; font-size: 13px; background-color: #FFF3CD; padding: 8px 9px; line-height: 18px;">
                        <i class="bi bi-info-circle-fill" style="color: #D32839;"></i>
                        The level of approval is visible until you claim the ticket
                    </div>
                @endif
            @endif
            @if ($isNeedLevelOfApproval)
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
                                            style="background-color: {{ $isTicketLevel1Approved ? '#3B4053' : '#757a8f' }};">
                                            {{ $level1Approver->profile->getNameInitial() }}
                                        </div>
                                    @endif
                                    <small class="approver__name">
                                        {{ $level1Approver->profile->getFullName() }}
                                        @if ($level1Approver->id == auth()->user()->id)
                                            <span class="text-muted">(You)</span>
                                        @endif
                                        @if ($ticketLevel1ApprovalApprovedBy == $level1Approver->id)
                                            <i class="bi bi-check-lg text-muted"></i>
                                        @endif
                                    </small>
                                </div>
                                @if (auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                                    @if ($level1Approver->id == auth()->user()->id)
                                        @if (!$isTicketLevel1Approved)
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
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex flex-column level__approval__container text-muted">
                    <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                        <small class="level__number__label"
                            style="{{ !$isTicketLevel1Approved ? 'color: #adadad !important;' : '' }}">
                            Level 2 {{ $level2Approvers->count() > 1 ? 'Approvers' : 'Approver' }}
                        </small>
                        @if ($isTicketLevel2Approved)
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill ms-1" style="font-size: 11px; color: #D32839;"></i>
                                <div class="border-0 text-muted" style="font-size: 0.75rem;">
                                    Approved
                                </div>
                            </div>
                        @endif
                    </div>
                    @foreach ($level2Approvers as $level2Approver)
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
                                            style="background-color: {{ $isTicketLevel2Approved ? '#3B4053' : '#757a8f' }};">
                                            {{ $level2Approver->profile->getNameInitial() }}
                                        </div>
                                    @endif
                                    <small class="approver__name {{ $isTicketLevel1Approved ? 'text-dark' : '' }}">
                                        {{ $level2Approver->profile->getFullName() }}
                                        @if ($ticketLevel2ApprovalApprovedBy == $level2Approver->id)
                                            <i class="bi bi-check-lg text-muted"></i>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
