<div>
    <div wire:ignore.self class="modal fade department__modal" id="addNewStoreModal" tabindex="-1"
        aria-labelledby="addNewStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewStoreModalLabel">Add new store</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="mb-1">
                                <label for="store_code" class="form-label form__field__label">Store Code</label>
                                <input type="text" wire:model.defer="store_code"
                                    class="form-control form__field @error('store_code') is-invalid @enderror" id="store_code"
                                    placeholder="Enter store code">
                                @error('store_code')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label for="store_name" class="form-label form__field__label">Store Name</label>
                                <input type="text" wire:model.defer="store_name"
                                    class="form-control form__field @error('store_name') is-invalid @enderror" id="store_name"
                                    placeholder="Enter store name">
                                @error('store_name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label for="store_group_id" class="form-label form__field__label">Store Group</label>
                                <div>
                                    <div id="select-store-group" wire:ignore></div>
                                </div>
                                @error('store_group_id')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="store" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Add New
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        let storeGroupOption = @json($storeGroups).map(storeGroup => ({
            label: storeGroup.name,
            value: storeGroup.id
        }));

        VirtualSelect.init({
            ele: '#select-store-group',
            options: storeGroupOption,
            search: true,
            required: true,
            markSearchResults: true,
            popupDropboxBreakpoint: '3000px',
        });

        let storeGroupSelect = document.querySelector('#select-store-group')

        storeGroupSelect.addEventListener('change', (event) => {
            @this.set('store_group_id', event.target.value);
        });

        // Clear the store group select option when form is reset
        window.addEventListener('clear-store-group-select-option', () => {
            storeGroupSelect.reset();
        });

        // Refresh store group options when they are updated
        window.addEventListener('refresh-store-group-options', (event) => {
            const storeGroups = event.detail.storeGroups;
            const newOptions = storeGroups.map(storeGroup => ({
                label: storeGroup.name,
                value: storeGroup.id
            }));
            storeGroupSelect.setOptions(newOptions);
        });
    </script>
@endpush
