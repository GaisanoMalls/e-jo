@php
    use App\Models\Role;
    use App\Models\Status;
@endphp

<div>
    @if (auth()->user()->hasRole([Role::SERVICE_DEPARTMENT_ADMIN, Role::AGENT]) &&
            ($ticket->status_id != Status::CLOSED && $ticket->status_id != Status::DISAPPROVED))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
                <label class="ticket__actions__label">Ticket Actions</label>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                        <button class="btn d-flex align-items-center justify-content-center" data-bs-toggle="modal"
                            data-bs-target="#assignTicketModal" wire:click="getCurrentTeamOrAgent"
                            style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; border: 1px solid rgb(223, 228, 233); color: #3e3d3d; font-weight: 500; background-color: #f3f4f6;">
                            Assign to team/agent
                        </button>
                    @endif
                    @if (auth()->user()->hasRole(Role::AGENT))
                        @if (!is_null($ticket->agent_id))
                            @if ($this->isRecommendationRequested())
                                <span class="alert d-flex gap-1 border-0 py-2 px-3" role="alert"
                                    style="font-size: 13px; background-color: #dffdef;">
                                    <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                                    The approval for the recommendation is requested.
                                </span>
                                {{ $ictRecommendationApprover?->approver->profile->getFullName }}
                            @else
                                <button class="btn d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal" data-bs-target="#requestForApprovalModal"
                                    wire:click="getCurrentTeamOrAgent"
                                    style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; border: 1px solid rgb(223, 228, 233); color: #3e3d3d; font-weight: 500; background-color: #f3f4f6;">
                                    Request for approval
                                </button>
                            @endif
                        @else
                            <span class="alert border-0 py-2 px-3" role="alert"
                                style="font-size: 13px; background-color: #F5F7F9;">
                                Action is hidden until it is claimed
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
