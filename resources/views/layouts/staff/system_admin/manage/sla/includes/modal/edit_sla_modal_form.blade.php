<div class="modal sla__modal" id="editSLA{{ $sla->id }}" tabindex="-1" aria-labelledby="editSLAModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewSLAModalLabel">Edit Service Level Agreement</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.service_level_agreements.update', $sla->id) }}" method="post"
                autocomplete="off" id="modalForm">
                @csrf
                @method('PUT')
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label for="countdown_approach" class="form-label form__field__label">Hours</label>
                                <input type="text" name="countdown_approach" class="form-control form__field"
                                    id="countdown_approach"
                                    value="{{ old('countdown_approach', $sla->countdown_approach) }}"
                                    placeholder="e.g. 24">
                                @error('countdown_approach', 'editSLA')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                                @if (session()->has('duplicate_name_error'))
                                <div class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ session()->get('duplicate_name_error') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label for="time_unit" class="form-label form__field__label">
                                    Time unit
                                </label>
                                <input type="text" name="time_unit" class="form-control form__field" id="time_unit"
                                    value="{{ old('time_unit', $sla->time_unit) }}" placeholder="e.g. 1 Day">
                                @error('time_unit', 'editSLA')
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
                        @if ($errors->editSLA->any() || session()->has('duplicate_name_error'))
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal"
                            onclick="window.location.href='{{ route('staff.manage.service_level_agreements.index') }}'">Cancel</button>
                        @else
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">Cancel</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>