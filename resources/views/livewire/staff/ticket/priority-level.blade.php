<div>
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2" style="color: {{ $ticket->priorityLevel->color }};">
            <i class="bi bi-flag-fill"></i>
            <p class="mb-0 ticket__details__priority">{{ $ticket->priorityLevel->name }}</p>
        </div>
        @if ($ticket->status_id != App\Models\Status::CLOSED && $ticket->approval_status !=
        App\Models\ApprovalStatus::DISAPPROVED )
        <button class="btn btn-sm p-0 btn__change__priority__level"
            style="font-size: 12px; color: blue; text-decoration: underline !important;" data-bs-toggle="modal"
            data-bs-target="#changePriorityLevelModal">Change</button>
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