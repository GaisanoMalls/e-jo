<div class="modal confirm__logout__modal" id="confirmLogout" tabindex="-1" aria-labelledby="confirmLogoutLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <form action="{{ route('auth.requester.logout') }}" method="post">
                @csrf
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h5 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm</h5>
                    <p class="mb-0" style="margin-bottom: -4px !important; font-weight: 500;">Are you sure you want to
                        logout?
                    </p>
                    <small class="text-muted" style="font-size: 14px;">You will be returned to the login page.</small>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-50 btn__cancel__logout btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn w-50 btn__confirm__logout btn__confirm__modal">Yes,
                        logout</button>
                </div>
            </form>
        </div>
    </div>
</div>
