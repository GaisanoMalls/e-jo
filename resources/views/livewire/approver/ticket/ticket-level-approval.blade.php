@php
    use App\Models\Status;
@endphp

<div wire:poll.visible.5s>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Level of Approval</label>
            @foreach ($approvalLevels as $level)
                @php $approvers = $this->fetchApprovers($level); @endphp
                @if ($approvers->count() > 0)
                    {{-- Display the level --}}
                    <div class="d-flex flex-column level__approval__container">
                        <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                            <small class="level__number__label">
                                Level {{ $level }}
                            </small>
                            @if ($ticket->status_id !== Status::DISAPPROVED)
                                @if ($this->islevelApproved($level) && $this->isApprovalApproved())
                                    <small class="fw-bold" style="color: #C73C3C; font-size: 0.75rem;">Approved</small>
                                @else
                                    <small class="fw-bold" style="color: #C73C3C; font-size: 0.75rem;">Pending</small>
                                @endif
                            @endif
                        </div>
                        @foreach ($approvers as $approver)
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between ps-3 position-relative level__approver__container">
                                    <div class="level__approval__approver__dot position-absolute rounded-circle">
                                    </div>
                                    <div class="d-flex align-items-center" style="padding: 4px 0 4px 0;">
                                        @if ($approver->profile->picture)
                                            <img src="{{ Storage::url($approver->profile->picture) }}" alt=""
                                                class="image-fluid level__approval__approver__picture">
                                        @else
                                            <div class="level__approval__approver__name__initial d-flex align-items-center p-2 me-2 justify-content-center text-white"
                                                @style([
                                                    'background-color: #3b4053' => $approver->isApprover(),
                                                    'background-color: #9DA85C' => $approver->isServiceDepartmentAdmin(),
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
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
