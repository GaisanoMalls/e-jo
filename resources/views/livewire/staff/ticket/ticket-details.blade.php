@php
    use App\Enums\ApprovalStatusEnum;
    use App\Models\Role;
@endphp

<div>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="ticket__details__card__body__right">
            <div class="d-flex align-items-center gap-2 mb-3">
                <small class="ticket__actions__label">Ticket Details</small>
                <div wire:loading class="spinner-border spinner-border-sm loading__spinner" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div wire:loading.class="text-muted" class="d-flex flex-column gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">
                        Approval status:
                    </small>
                    <small class="ticket__details__info">
                        @if ($ticket->approval_status == ApprovalStatusEnum::APPROVED)
                            <i class="fa-solid fa-circle-check me-1" style="color: green; font-size: 11px;"></i>
                            Approved
                        @elseif ($ticket->approval_status == ApprovalStatusEnum::FOR_APPROVAL)
                            <i class="fa-solid fa-paper-plane me-1" style="color: orange; font-size: 11px;"></i>
                            For Approval
                        @elseif ($ticket->approval_status == ApprovalStatusEnum::DISAPPROVED)
                            <i class="fa-solid fa-xmark me-1" style="color: red; font-size: 11px;"></i>
                            Disapproved
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">Branch:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-location-dot me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->branch->name }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">
                        Service department:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-gears me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->serviceDepartment?->name }}
                        @if ($ticket->helpTopic->serviceDepartmentChild)
                            <span>/ {{ $ticket->helpTopic->serviceDepartmentChild->name }}</span>
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">Team:</small>
                    <small class="position-relative ticket__details__info">
                        <i class="fa-solid fa-people-group me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->getTeams() }}
                        @if (
                            $ticket->team_id &&
                                auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                            <i wire:click="removeAssignedTeam" class="bi bi-x ms-2 text-danger position-absolute"
                                style="font-size: 17px; transform: translateY(-10%); margin-left: 1px !important;"></i>
                        @endif
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">
                        Help topic:
                    </small>
                    <small class="ticket__details__info">
                        <i class="bi bi-question-circle-fill me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->helpTopic->name ?? '' }}
                    </small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">
                        Assigned agent:
                    </small>
                    <small
                        class="position-relative ticket__details__info {{ $ticket->agent_id != null ? '' : 'not__set' }}">
                        <i class="fa-solid fa-user-check me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->agent_id != null ? $ticket->agent->profile->getFullName() : '' }}
                        @if (
                            $ticket->agent_id &&
                                auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                            <i wire:click="removeAssignedAgent" class="bi bi-x ms-2 text-danger position-absolute"
                                style="font-size: 17px; transform: translateY(-10%); margin-left: 1px !important;"></i>
                        @endif
                    </small>
                </div>
                <div class="my-2" style="border-top: 0.08rem solid #e6edef;"></div>
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                    <small class="ticket__details__info__label">
                        Service Level Agreement:</small>
                    <div class="d-flex align-items-center gap-2">
                        @if ($this->isSlaApproved($ticket))
                            @livewire('sla-timer', ['ticket' => $ticket])
                        @endif
                        <!-- Progress bar 4 -->
                        <div class="progress mx-auto"
                            data-value='{{ $this->ticketSLATimer($ticket)['percentageElapsed'] }}'>
                            <span
                                class="progress-left {{ $this->isSlaApproved($ticket) && !$this->isSlaOverdue($ticket) ? 'bx-flashing' : '' }}">
                                <span wire:ignore class="progress-bar" @style([
                                    'border-color: #8BE78B;' => $this->ticketSLATimer($ticket)['percentageElapsed'] >= 0 && $this->ticketSLATimer($ticket)['percentageElapsed'] <= 49,
                                    'border-color: #F79500;' => $this->ticketSLATimer($ticket)['percentageElapsed'] >= 50 && $this->ticketSLATimer($ticket)['percentageElapsed'] <= 79,
                                    'border-color: #940000;' => $this->ticketSLATimer($ticket)['percentageElapsed'] >= 80 && $this->ticketSLATimer($ticket)['percentageElapsed'] <= 100,
                                ])></span>
                            </span>
                            <span
                                class="progress-right {{ $this->isSlaApproved($ticket) && !$this->isSlaOverdue($ticket) ? 'bx-flashing' : '' }}">
                                <span wire:ignore class="progress-bar" @style([
                                    'border-color: #8BE78B;' => $this->ticketSLATimer($ticket)['percentageElapsed'] >= 0 && $this->ticketSLATimer($ticket)['percentageElapsed'] <= 49,
                                    'border-color: #F79500;' => $this->ticketSLATimer($ticket)['percentageElapsed'] >= 50 && $this->ticketSLATimer($ticket)['percentageElapsed'] <= 79,
                                    'border-color: #940000;' => $this->ticketSLATimer($ticket)['percentageElapsed'] >= 80 && $this->ticketSLATimer($ticket)['percentageElapsed'] <= 100,
                                ])></span>
                            </span>
                            <div
                                class="progress-value w-100 h-100 rounded-circle d-flex flex-column align-items-center justify-content-center">
                                <div class="fw-bold progress__initial__value">
                                    {{ $this->getSLADays($ticket) }}
                                </div>
                                <span class="unit">{{ $this->getSLAUnit($ticket) }}</sup>
                            </div>
                        </div>
                        <!-- END -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#assignTicketModal').modal('hide');
        });
    </script>
@endpush

@push('extra')
    <script>
        $(function() {
            $(".progress").each(function() {
                var value = $(this).attr('data-value');
                var left = $(this).find('.progress-left .progress-bar');
                var right = $(this).find('.progress-right .progress-bar');

                if (value > 0) {
                    if (value <= 50) {
                        right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
                    } else {
                        right.css('transform', 'rotate(180deg)')
                        left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
                    }
                }
            })

            function percentageToDegrees(percentage) {

                return percentage / 100 * 360

            }

        });
    </script>
@endpush
