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
                @include('layouts.staff.ticket.forms.ticket_priority_forn')
                @include('layouts.staff.ticket.forms.assign_ticket_to_another_agent_form')
                <button type="submit" class="btn mt-2 modal__footer__button modal__btnsubmit__bottom"
                    id="btnSaveAssignTicketToAnotherAgent">Save</button>
                {{-- <div class="mt-4">
                    @include('layouts.staff.ticket.forms.transfer_ticket_to_other_department_form')
                    @include('layouts.staff.ticket.forms.transfer_ticket_to_other_branch_form')
                </div> --}}
            </div>
        </div>
    </div>
</div>