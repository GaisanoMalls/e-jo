<div class="modal reason__modal" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 modal__content">
            <div class="modal-header p-0 border-0">
                <h6 class="title mb-0">Reason of Disapproval</h6>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="reason__content mt-3 py-2 px-3 bg-light rounded-3">
                {!! $reason->description !!}
            </div>
        </div>
    </div>
</div>