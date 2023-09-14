<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 login__form__user__type">Welcome Back</h5>
        <h6 class="mb-0 form__name">Sign in</h6>
    </div>

    <form wire:submit.prevent="login" novalidate>
        @if (session()->has('error'))
        <ul class='form__errors mb-2 text-center'>
            <li>{{ session('error') }}</li>
        </ul>
        @endif
        <div class="my-2">
            <label class="form-label input__login__label" for="email">Email address</label>
            <input type="email" id="email" class="form-control login__input__field @error('email') is-invalid @enderror"
                placeholder="Enter your email" wire:model.debounce.500ms="email">
            @error('email')
            <span class="text-danger custom__field__message">{{ $message }}</span>
            @enderror
        </div>
        <div class="my-2">
            <label class="form-label input__login__label" for="password">Password</label>
            <input type="password" id="password"
                class="form-control login__input__field @error('password') is-invalid @enderror"
                placeholder="Enter your password" wire:model.debounce.500ms="password">
            @error('password')
            <span class="text-danger custom__field__message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn mt-3 w-100 btn-block login__button">
            Sign In
        </button>
        <a href="{{ route('forgot_password') }}" class="mt-4 link">Forgot password?</a>
    </form>
</div>