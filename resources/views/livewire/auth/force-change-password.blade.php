<div>
    <form wire:submit.prevent="save" class="login__form">
        <div class="mb-4">
            <h5 class="text-center">Change your temporary password</h5>
            <p class="text-muted text-center mb-0">For security, please set a new password before continuing.</p>
        </div>

        <div class="form-group mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input id="current_password" type="password" wire:model.defer="current_password" class="form-control input__field">
            @error('current_password')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input id="new_password" type="password" wire:model.defer="new_password" class="form-control input__field">
            @error('new_password')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input id="confirm_password" type="password" wire:model.defer="confirm_password" class="form-control input__field">
            @error('confirm_password')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn mt-3 w-100 btn-block login__button">Update Password</button>
    </form>
</div>


