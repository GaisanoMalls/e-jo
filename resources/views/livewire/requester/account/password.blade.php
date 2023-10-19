<div>
    <form wire:submit.prevent="savePassword">
        <div class="card d-flex flex-column gap-4 account__info__fields__container">
            <div class="account__form__container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label input__field__label">Current Password</label>
                            <input type="password" wire:model.defer="current_password"
                                class="form-control input__field @error('current_password') is-invalid @enderror"
                                id="currentPassword">
                            @error('current_password')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">{{-- DO NOT DELETE. JUST LEAVE IT EMPTY --}}</div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label input__field__label">New Password</label>
                            <input type="password" wire:model.defer="new_password"
                                class="form-control input__field @error('new_password') is-invalid @enderror"
                                id="newPassword">
                            @error('new_password')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label input__field__label">Confirm Password</label>
                            <input type="password" wire:model.defer="confirm_password"
                                class="form-control input__field @error('confirm_password') is-invalid @enderror"
                                id="confirmPassword">
                            @error('confirm_password')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn w-auto btn__save__account" wire:loading.attr="disabled">
                            <span wire:loading wire:target="savePassword" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>