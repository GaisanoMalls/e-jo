<div>
    <div wire:ignore.self class="modal fade sla__modal" id="addNewSLAModal" tabindex="-1"
        aria-labelledby="addNewSLAModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewSLAModalLabel">Add new SLA</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveSLA">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="hours" class="form-label form__field__label">Hours</label>
                                    <input type="text" wire:model="hours"
                                        class="form-control form__field
                                    @error('hours') is-invalid @enderror"
                                        id="hours" placeholder="e.g. 24">
                                    @error('hours')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="time_unit" class="form-label form__field__label">
                                        Time unit
                                    </label>
                                    <input type="text" wire:model="time_unit"
                                        class="form-control form__field
                                    @error('time_unit') is-invalid @enderror"
                                        id=" time_unit" placeholder="e.g. 1 Day">
                                    @error('time_unit')
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
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="saveSLA" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Add New
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                                data-bs-dismiss="modal" wire:click="clearFormFields">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
