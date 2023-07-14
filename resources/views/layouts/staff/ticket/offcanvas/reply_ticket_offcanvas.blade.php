<div class="offcanvas offcanvas-bottom custom__reply__ticket__offcanvas m-auto" tabindex="-1"
    id="offcanvasReplyTicketForm" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title custom__offcanvas__title" id="offcanvasBottomLabel">Write your reply</h5>
        <button class="btn d-flex align-items-center justify-content-center offcanvas__close__button"
            data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body small">
        <div class="mb-4 d-flex flex-column gap-3 reply__ticket__info">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="https://preview.keenthemes.com/metronic8/demo27/assets/media/avatars/300-1.jpg"
                        class="sender__profile" alt="">
                    <p class="mb-0" style="font-size: 0.813rem; font-weight: 500;">Sams's latest reply</p>
                </div>
                <p class="mb-0 time__sent">6 minutes ago</p>
            </div>
            <p class="mb-0 ticet__description">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor. incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrudexercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor. incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrudexercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>
        @include('layouts.staff.ticket.forms.reply_ticket_form')
    </div>
</div>
