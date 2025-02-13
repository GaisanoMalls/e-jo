@php
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum as ApprovalStatus;
@endphp

<div>
    @if (auth()->user()->isServiceDepartmentAdmin() &&
            ($ticket->status_id != Status::CLOSED && $ticket->status_id != Status::DISAPPROVED && $ticket->approval_status == ApprovalStatus::APPROVED))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
                <label class="ticket__actions__label">Ticket Actions</label>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    @if (auth()->user()->isServiceDepartmentAdmin())
                        <button class="btn d-flex align-items-center justify-content-center" data-bs-toggle="modal"
                            data-bs-target="#requestForApprovalModal" wire:click="getCurrentTeamOrAgent"
                            style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; border: 1px solid rgb(223, 228, 233); color: #3e3d3d; font-weight: 500; background-color: #f3f4f6;">
                            Request for approval
                        </button>
                        <button class="btn d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#assignTicketModal"
                            wire:click="getCurrentTeamOrAgent"
                            style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; border: 1px solid rgb(223, 228, 233); color: #3e3d3d; font-weight: 500; background-color: #f3f4f6;">
                            Assign to team/agent
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
