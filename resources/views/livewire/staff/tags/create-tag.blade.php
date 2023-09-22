<div>
    <div wire:ignore.self class="modal tag__modal" id="addNewTagModal" tabindex="-1"
        aria-labelledby="addNewTagModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewTagModalLabel">Add new tag</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveTag">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="name" class="form-label form__field__label">Name</label>
                                    <input type="text"
                                        class="form-control @error('name') is-invalid @enderror form__field" id="name"
                                        placeholder="Enter tag name" wire:model.debounce.500ms="name">
                                    @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send"
                                wire:loading.attr="disabled">
                                Save
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="clearFormField">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>