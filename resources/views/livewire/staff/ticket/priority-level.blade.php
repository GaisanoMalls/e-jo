@php
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

<div>
    <button class="btn btn-sm p-0 rounded-0 d-flex align-items-center justify-content-center gap-2 btn__change__priority__level"
        style="font-size: 0.7rem; color: #6c757d; border-bottom: 1px solid green !important;" data-bs-toggle="modal"
        data-bs-target="#changePriorityLevelModal" @disabled($ticket->status_id === Status::CLOSED && $ticket->approval_status === ApprovalStatusEnum::DISAPPROVED)>
        <p class="mb-0 ticket__details__priority" style="color: {{ $ticket->priorityLevel->color }};">{{ $ticket->priorityLevel->name }}</p>
        <i class="bi bi-pencil-fill" style="font-size: 12px;"></i>
    </button>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#changePriorityLevelModal').modal('hide');
        });
    </script>
@endpush
