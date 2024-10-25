@php
    use App\Models\Role;
    use App\Models\Status;
@endphp

<div>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Level of Approval</label>
            @foreach ($approvalLevels as $level)
                {{-- To check if the particular level has already been displayed --}}
                @php $levelIsDisplayed = false; @endphp
                @foreach ($ticketApprovals as $ticketApproval)
                    {{-- Check the equality of the help topic approver's level and the level of approvals, and display the approval level if it hasn't been shown yet. --}}
                    @if ($ticketApproval->helpTopicApprover->level === $level && !$levelIsDisplayed)
                        @php $levelIsDisplayed = true; @endphp
                        <div class="d-flex flex-column level__approval__container">
                            <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                                <small class="level__number__label">
                                    Level {{ $level }}
                                </small>
                                @if ($ticket->status_id !== Status::DISAPPROVED)
                                    @if ($this->islevelApproved($level) && $this->isApprovalApproved())
                                        <small class="fw-bold"
                                            style="color: #C73C3C; font-size: 0.75rem;">Approved</small>
                                    @else
                                        <small class="fw-bold" style="color: #C73C3C; font-size: 0.75rem;">Pending</small>
                                    @endif
                                @endif
                            </div>
                            @foreach ($this->fetchedApprovers($level) as $approver)
                                <div class="d-flex flex-column">
                                    <div
                                        class="d-flex align-items-center justify-content-between position-relative ps-3 level__approver__container">
                                        <div class="level__approval__approver__dot position-absolute rounded-circle">
                                        </div>
                                        <div class="d-flex align-items-center" style="padding: 4px 0 4px 0;">
                                            @if ($approver->profile->picture)
                                                <img src="{{ Storage::url($approver->profile->picture) }}"
                                                    class="image-fluid level__approval__approver__picture">
                                            @else
                                                <div class="level__approval__approver__name__initial d-flex align-items-center p-2 me-2 justify-content-center text-white"
                                                    @style([
                                                        'background-color: #3b4053' => $approver->hasRole(Role::APPROVER),
                                                        'background-color: #9DA85C' => $approver->hasRole(Role::SERVICE_DEPARTMENT_ADMIN),
                                                    ])>
                                                    {{ $approver->profile->getNameInitial() }}
                                                </div>
                                            @endif
                                            <small class="approver__name">
                                                {{ $approver->profile->getFullName }}
                                                @if ($approver->id == auth()->user()->id)
                                                    <span class="text-muted">(You)</span>
                                                @endif
                                            </small>
                                            @if ($ticketApproval->helpTopicApprover->user_id === $approver->id || $this->isApprovalApproved())
                                                <i class="bi bi-check2 ms-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</div>
