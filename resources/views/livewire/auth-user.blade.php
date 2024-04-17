<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 login__form__user__type">Welcome Back</h5>
        <h6 class="mb-0 form__name">Sign in</h6>
    </div>

    <form wire:submit.prevent="login" novalidate>
        <div class="my-2">
            <label class="form-label input__login__label" for="email">Email address</label>
            <input type="email" id="email"
                class="form-control login__input__field @error('email') is-invalid @enderror"
                placeholder="Enter your email" wire:model="email">
            @error('email')
                <span class="text-danger custom__field__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="my-2">
            <label class="form-label input__login__label" for="password">Password</label>
            <input type="password" id="password"
                class="form-control login__input__field @error('password') is-invalid @enderror"
                placeholder="Enter your password" wire:model="password">
            @error('password')
                <span class="text-danger custom__field__message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit"
            class="btn mt-3 w-100 btn-block d-flex align-items-center justify-content-center gap-2 login__button">
            <span wire:loading wire:target="login" class="spinner-border spinner-border-sm" role="status"
                aria-hidden="true">
            </span>
            <span wire:loading.remove wire:target="login">Sign In</span>
            <span wire:loading wire:target="login">Signing in...</span>
        </button>
        <a href="{{ route('forgot_password') }}" class="mt-4 link">Forgot password?</a>
    </form>
</div>
