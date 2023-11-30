<div>
    @if (auth()->user()->hasRole(App\Models\Role::SERVICE_DEPARTMENT_ADMIN) && ($ticket->status_id
    != App\Models\Status::CLOSED && $ticket->status_id
    != App\Models\Status::DISAPPROVED))
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Ticket Actions</label>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <ul class="mb-0 ticket__actions" style="padding-left: 1.1rem;">
                    <li>
                        <small>Move this ticket to other team.</small>
                    </li>
                    <li>
                        <small>Assign this ticket to other agent.</small>
                    </li>
                </ul>
                <button class="btn btn-block bg-dark btn__ticket__set__action" data-bs-toggle="modal"
                    data-bs-target="#assignTicketModal" wire:click="getCurrentTeamOrAgent">
                    Assign to team/agent
                </button>
            </div>
        </div>
    </div>
    @endif
</div>