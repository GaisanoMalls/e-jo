<div class="modal status__modal" id="addNewTicketStatusModal" tabindex="-1"
    aria-labelledby="addNewTicketStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewTicketStatusModalLabel">Add new status</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.ticket_statuses.store') }}" method="post" autocomplete="off"
                id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="mb-2">
                            <label for="name" class="form-label form__field__label">Name</label>
                            <input type="text" name="name" class="form-control form__field" id="name"
                                value="{{ old('name') }}" placeholder="Type here...">
                            @error('name', 'storeTicketStatus')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="color" class="form-label form__field__label">Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="colorSelect" class="form-control-color form__field p-1"
                                    id="statusColorSelect" value="{{ old('name') }}" placeholder="Type here...">
                                <input type="text" name="color" class="form-control form__field" value=""
                                    id="statusColorHexVal">
                            </div>
                            @error('name', 'storeTicketStatus')
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
                        <button type="submit" class="btn m-0 btn__modal__footer btn__send">Save</button>
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
