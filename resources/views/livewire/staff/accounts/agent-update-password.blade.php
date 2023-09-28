<div wire:ignore.self class="modal slideIn animate edit__password__modal" id="editPasswordModal"
    aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewTeamModalLabel">Update Password</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form wire:submit.prevent="updatePassword({{ $agent->id }})">
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="mb-2">
                            <label for="new_password" class="form-label form__field__label">New password</label>
                            <input type="password" wire:model="new_password"
                                class="form-control form__field @error('new_password') is-invalid @enderror"
                                id="new_password" placeholder="Enter the new password">
                            @error('new_password')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="confirm_password" class="form-label form__field__label">Confirm
                                password</label>
                            <input type="password" wire:model="confirm_password"
                                class="form-control form__field @error('confirm_password') is-invalid @enderror"
                                id="confirm_password" placeholder="Re-type the new password to confirm">
                            @error('confirm_password')
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
                        <button type="button" class="btn m-0 btn__update__password btn__cancel" data-bs-dismiss="modal"
                            wire:click="clearFormFields">Cancel</button>
                        <button type="submit"
                            class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__update__password btn__send">
                            <span wire:loading wire:target="updatePassword({{ $agent->id }})"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>