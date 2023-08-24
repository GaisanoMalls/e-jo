<div class="modal edit__password__modal" id="editPasswordModal" aria-labelledby="editPasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewTeamModalLabel">Update Password</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.user_account.approver.update_password', $approver->id) }}"
                method="post">
                @csrf
                @method('PUT')
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="mb-2">
                            <label for="new_password" class="form-label form__field__label">New password</label>
                            <input type="password" name="new_password" class="form-control form__field"
                                id="new_password" value="" placeholder="Enter the new password">
                            @error('new_password', 'updatePassword')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="confirm_password" class="form-label form__field__label">Confirm
                                password</label>
                            <input type="password" name="confirm_password" class="form-control form__field"
                                id="confirm_password" value="" placeholder="Re-type the new password to confirm">
                            @error('confirm_password', 'updatePassword')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn m-0 btn__update__password btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-0 btn__update__password btn__send">Save new password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>