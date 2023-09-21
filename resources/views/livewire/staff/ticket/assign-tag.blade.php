<div>
    <div wire:ignore.self class="modal ticket__actions__modal" id="ticketTagModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Ticket Tagging</h6>
                </div>
                <div class="modal__body">
                    <form wire:submit.prevent="saveAssignTicketTag">
                        <div class="my-2">
                            <label class="ticket__actions__label mb-2">Assign tag</label>
                            <div>
                                <div id="select-tag" wire:ignore></div>
                            </div>
                            @error('team')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                            <input type="hidden" value="{{ $ticket->tags->pluck('id') }}" id="current-tags">
                        </div>
                        <button type="submit" class="btn mt-2 modal__footer__button modal__btnsubmit__bottom"
                            wire:click="$emit('loadTicketTags')">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
<script>
    let tagOption = [
        @foreach ($tags as $tag)
            {
                label: "{{ $tag->name }}",
                value: "{{ $tag->id }}"
            },
        @endforeach
    ];

    VirtualSelect.init({
        ele: '#select-tag',
        options: tagOption,
        search: true,
        multiple: true,
        showValueAsTags: true,
        markSearchResults: true,
        hasOptionDescription: true
    });

    let tagSelect = document.querySelector('#select-tag')
    let currentTags = document.querySelector('#current-tags');

    //TODO - Not yet solved.
    tagSelect.selectedValue([currentTags.value]);

    tagSelect.addEventListener('change', () => {
        let tagId = tagSelect.value;
        @this.set('selectedTags', tagId);
    });

</script>
@endpush