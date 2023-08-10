@extends('layouts.auth.base', ['title' => 'Forgot Password'])

@section('form-title')
<div class="d-flex flex-column mb-3">
    <h5 class="mb-0 login__form__user__type">Forgot Password</h5>
    <small class="my-2 text-muted">Enter the email address associated with your account and we'll send you a link to
        reset your
        password.</small>
</div>
@endsection

@section('form-section')
<form method="post" action="">
    @csrf
    @if (session()->has('error'))
    <ul class='form__errors mb-2 text-center'>
        <li>{{ session('error') }}</li>
    </ul>
    @endif
    <div class="my-2">
        <label class="form-label input__login__label">Email address</label>
        <input type="email" name="email" value="{{ old('email') }}"
            class="form-control login__input__field @error('email') is-invalid @enderror"
            placeholder="Enter your email">
        @error('email')
        <span class="text-danger custom__field__message">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn mt-3 w-100 btn-block login__button">
        Send Password Reset Link
    </button>
    <a href="{{ route('login') }}" class="link mt-4 d-flex align-items-center justify-content-center gap-2">
        <i class="fa-solid fa-angle-left"></i>
        Back to sign in
    </a>
</form>
@endsection