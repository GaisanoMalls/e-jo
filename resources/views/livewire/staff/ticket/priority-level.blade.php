@php
    use App\Models\Status;
    use App\Enums\ApprovalStatusEnum;
@endphp

<div>
    <div class="d-flex align-items-center gap-2">
        <div class="d-flex align-items-center gap-2" style="color: {{ $ticket->priorityLevel->color }};">
            <i class="bi bi-flag-fill"></i>
            <p class="mb-0 ticket__details__priority">{{ $ticket->priorityLevel->name }}</p>
        </div>
        @if ($ticket->status_id != Status::CLOSED && $ticket->approval_status != ApprovalStatusEnum::DISAPPROVED)
            <button class="btn btn-sm p-0 btn__change__priority__level" style="font-size: 0.7rem; color: #6c757d;"
                data-bs-toggle="modal" data-bs-target="#changePriorityLevelModal"><i class="bi bi-pen"></i></button>
        @endif
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#changePriorityLevelModal').modal('hide');
        });
    </script>
@endpush
