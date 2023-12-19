<div>
    <div wire:ignore.self class="modal fade ticket__actions__modal" id="ticketTagModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Ticket Tagging</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
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
                        </div>
                        <button type="submit"
                            class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                            <span wire:loading wire:target="saveAssignTicketTag"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        const tagOption = [
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
            hasOptionDescription: true,
            popupDropboxBreakpoint: '3000px',
        });

        const tagSelect = document.querySelector('#select-tag')
        tagSelect.setValue({{ $ticket->tags->pluck('id') }});

        tagSelect.addEventListener('change', () => {
            @this.set('selectedTags', tagSelect.value);
        });

        // Clear all selected tags in the select option when clear button is clicked.
        window.addEventListener('clear-tag-select-option', () => {
            tagSelect.reset();
        });

        // Update the selected tags after removing specific tag.
        window.addEventListener('update-tag-select-option', (event) => {
            tagSelect.setValue(event.detail.tagIds);
        });

        // Get the current assigned tags when button "Add/Remove" is clicked.
        window.addEventListener('get-current-assigned-tags', (event) => {
            tagSelect.setValue(event.detail.tagIds);
        });
    </script>
@endpush
