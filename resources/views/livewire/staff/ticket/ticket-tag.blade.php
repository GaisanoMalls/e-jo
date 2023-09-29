<div>
    <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
        <div class="ticket__details__card__body__right">
            <div class="mb-3 d-flex justify-content-between">
                <div class="d-flex align-items-center gap-2 mb-">
                    <small class="ticket__actions__label">Tags</small>
                    <div wire:loading class="spinner-border spinner-border-sm loading__spinner" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if ($ticket->status_id != \App\Models\Status::CLOSED)
                    @if (!$ticket->tags->isEmpty())
                    <button type="button" class="btn__clear__tags" wire:click="clearTags">
                        <i class="bi bi-trash"></i>
                        Clear
                    </button>
                    @endif
                    <button type="button" class="btn__add__tags" data-bs-toggle="modal" data-bs-target="#ticketTagModal"
                        wire:click="getCurrentAssignedTags">
                        <i class="bi bi-plus-lg"></i>
                        Add/Remove
                    </button>
                    @endif
                </div>
            </div>
            @if (!$ticket->tags->isEmpty())
            <div class="d-flex flex-wrap align-items-center gap-2">
                @foreach ($ticket->tags as $tag)
                <div class="d-flex align-items-center shadow-sm gap-2 ticket__tag">
                    <a href=""
                        class="text-white tag__link {{ $ticket->status_id == \App\Models\Status::CLOSED ? 'me-2' : '' }}">
                        {{ $tag->name }}
                    </a>
                    @if ($ticket->status_id != \App\Models\Status::CLOSED)
                    <div wire:key="ticket-tag-{{ $tag->id }}" wire:click="removeTag({{ $tag->id }})"
                        class="d-flex align-items-center justify-content-center remove__tag">
                        <i class="bi bi-x"></i>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="rounded-3" style="font-size: 0.8rem; padding: 9px 18px; background-color: #F5F7F9;">
                Empty tags
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event =>{
        $('#ticketTagModal').modal('hide');
    });
</script>
@endpush