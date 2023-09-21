<div>
    <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
        <div class="ticket__details__card__body__right">
            <div class="mb-3 d-flex justify-content-between">
                <small class="ticket__actions__label">Tags</small>
                <button type="button" class="btn__add__tags" data-bs-toggle="modal" data-bs-target="#ticketTagModal">
                    <i class="fa-solid fa-plus"></i>
                    Add
                </button>
            </div>
            @if (!$ticket->tags->isEmpty())
            <div class="d-flex flex-wrap align-items-center gap-2">
                @foreach ($ticket->tags as $tag)
                <a href="" class="btn btn-sm ticket__tag">{{ $tag->name }}</a>
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