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
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">
                        Approval status:
                    </small>
                    <small class="ticket__details__info">
                        @if ($ticket->approval_status == App\Models\ApprovalStatus::APPROVED)
                            <i class="fa-solid fa-circle-check me-1" style="color: green; font-size: 11px;"></i>
                            Approved
                        @elseif ($ticket->approval_status == App\Models\ApprovalStatus::FOR_APPROVAL)
                            <i class="fa-solid fa-paper-plane me-1" style="color: orange; font-size: 11px;"></i>
                            For Approval
                        @elseif ($ticket->approval_status == App\Models\ApprovalStatus::DISAPPROVED)
                            <i class="fa-solid fa-xmark me-1" style="color: red; font-size: 11px;"></i>
                            Disapproved
                        @else
                            ----
                        @endif
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">Branch:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-location-dot me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->branch->name }}
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">
                        Service department:</small>
                    <small class="ticket__details__info">
                        <i class="fa-solid fa-gears me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->serviceDepartment->name }}
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">Team:</small>
                    <small class="position-relative ticket__details__info">
                        <i class="fa-solid fa-people-group me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->team->name ?? '----' }}
                        @if (
                            $ticket->team_id &&
                                auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                            <i wire:click="removeAssignedTeam" class="bi bi-x ms-2 text-danger position-absolute"
                                style="font-size: 17px; transform: translateY(-10%); margin-left: 1px !important;"></i>
                        @endif
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">
                        Help topic:
                    </small>
                    <small class="ticket__details__info">
                        <i class="bi bi-question-circle-fill me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->helpTopic->name ?? '----' }}
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">
                        Assigned agent:
                    </small>
                    <small
                        class="position-relative ticket__details__info {{ $ticket->agent_id != null ? '' : 'not__set' }}">
                        <i class="fa-solid fa-user-check me-1 text-muted" style="font-size: 11px;"></i>
                        {{ $ticket->agent_id != null ? $ticket->agent->profile->getFullName() : '----' }}
                        @if (
                            $ticket->agent_id &&
                                auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN))
                            <i wire:click="removeAssignedAgent" class="bi bi-x ms-2 text-danger position-absolute"
                                style="font-size: 17px; transform: translateY(-10%); margin-left: 1px !important;"></i>
                        @endif
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="ticket__details__info__label">
                        SLA:</small>
                    <div class="d-flex align-items-center gap-2">
                        {{-- @if ($isApprovedForSLA) --}}
                            <small class="rounded-2" id="slaTimer" style="font-size: 11px; padding: 2px 5px;"></small>
                        {{-- @endif --}}
                        <small class="ticket__details__info" id="slaDays">
                            <i class="fa-solid fa-clock me-1 text-muted" style="font-size: 11px;"></i>
                            {{ $ticket->sla->time_unit ?? '----' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('extra')
    <script>
        const slaTimer = document.querySelector('#slaTimer')
        // Set the number of days for the countdown
        const slaDays = parseInt(@json($ticket->sla->time_unit[0]));
        const hoursPerDay = 24;

        let targetDate = localStorage.getItem('targetDate');
        if (!targetDate) {
            targetDate = new Date();
            targetDate.setHours(targetDate.getHours() + slaDays * hoursPerDay);
            localStorage.setItem('targetDate', targetDate);
        } else {
             targetDate = new Date(targetDate);
        }

        // Update the countdown every second
        const countdownInterval = setInterval(updateCountdown, 1000);

        function updateCountdown() {
            // Get the current date and time
            const currentDate = new Date().getTime();

            // Calculate the time remaining
            const timeRemaining = targetDate - currentDate;

            // Calculate days, hours, and minutes
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));

            // Display the countdown in the specified format
            slaTimer.innerHTML = `${days} days, ${hours} hours, ${minutes} minutes`;

            // Check if the countdown has reached zero
            if (timeRemaining <= 0) {
                clearInterval(countdownInterval); // Stop the countdown
                slaTimer.innerHTML = 'SLA expired';
                slaTimer.style.backgroundColor = 'red';
                slaTimer.style.color = 'white';
                localStorage.removeItem('targetDate');
            }
        }
    </script>
@endpush

@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#assignTicketModal').modal('hide');
        });
    </script>
@endpush
