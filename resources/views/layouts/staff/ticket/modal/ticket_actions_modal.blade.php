<!-- Department Modal -->
<div class="modal ticket__actions__modal" id="ticketActionModalForm" tabindex="-1" aria-labelledby="modalFormLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom__modal">
        <div class="modal-content d-flex flex-column custom__modal__content">
            <div class="modal__header d-flex justify-content-between align-items-center">
                <h6 class="modal__title">Choose ticket actions</h6>
                <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                    data-bs-dismiss="modal" id="btnCloseModal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal__body">
                <div class="my-2">
                    <label class="ticket__actions__label mb-2">Assign to agent</label>
                    <div class="input-group">
                        <select class="form-select p-0 border-0 ticket__dropdown__select" id="selectAssignToAgent">
                            <option value="" selected disabled>Choose an agent</option>
                            @foreach ($approvers as $approver)
                            <option value="{{ $approver->id }}">
                                {{ $approver->profile->getFullName() }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn mt-2 modal__footer__button modal__btnsubmit__bottom"
                    id="btnSaveAssignTicketToAnotherAgent">Save</button>
            </div>
        </div>
    </div>
</div>