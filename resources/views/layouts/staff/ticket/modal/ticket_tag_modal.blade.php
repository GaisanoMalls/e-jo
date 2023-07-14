<!-- Department Modal -->
<div class="modal ticket__actions__modal" id="ticketTagModalForm" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom__modal">
        <div class="modal-content custom__modal__content">
            <div class="modal__header d-flex justify-content-between align-items-center">
                <h6 class="modal__title">Choose a ticket action</h6>
            </div>
            <div class="modal__body">
                @include('layouts.staff.ticket.forms.ticket_tag_form')
            </div>
        </div>
    </div>
</div>
