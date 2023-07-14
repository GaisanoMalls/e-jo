<div class="modal sla__modal" id="addNewSLAModal" tabindex="-1" aria-labelledby="addNewSLAModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewSLAModalLabel">Add new SLA</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.service_level_agreements.store') }}" method="post" autocomplete="off"
                id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label for="countdown_approach" class="form-label form__field__label">Hours</label>
                                <input type="text" name="countdown_approach" class="form-control form__field"
                                    id="countdown_approach" value="{{ old('countdown_approach') }}"
                                    placeholder="Type here...">
                                @error('countdown_approach', 'storeSLA')
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
                                <input type="text" name="time_unit" class="form-control form__field" id="time_unit"
                                    value="{{ old('time_unit') }}" placeholder="Type here...">
                                @error('time_unit', 'storeSLA')
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
                        <button type="submit" class="btn m-0 btn__modal__footer btn__send">Save</button>
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
