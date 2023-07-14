@extends('layouts.auth.base', ['title' => 'Clerk'])

@section('form-title')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0 login__form__user__type">Requester</h5>
    <h6 class="mb-0 form__name">Sign in</h6>
</div>
@endsection

@section('form-section')
<form method="post" action="{{ route('auth.requester.authenticate') }}">
    @csrf
    @if (session()->has('error'))
    <ul class='form__errors mb-2 text-center'>
        <li>{{ session('error') }}</li>
    </ul>
    @endif
    <div class="my-2">
        <label class="form-label input__login__label" for="email">Email address</label>
        <input type="email" name="email" value="{{ old('email') }}" id="email"
            class="form-control login__input__field @error('email') is-invalid @enderror"
            placeholder="Enter your email">
        @error('email')
        <span class="text-danger custom__field__message">{{ $message }}</span>
        @enderror
    </div>
    <div class="my-2">
        <label class="form-label input__login__label" for="password">Password</label>
        <input type="password" name="password" id="password"
            class="form-control login__input__field @error('password') is-invalid @enderror"
            placeholder="Enter your password">
        @error('password')
        <span class="text-danger custom__field__message">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn mt-3 w-100 btn-block login__button">
        Sign In
    </button>
    <a href="{{ route('login') }}" type="button"
        class="btn bg-light my-3 w-100 btn-block text-dark login__back__button">
        Back
    </a>
    @include('layouts.auth.includes.forgot_password_link')
</form>
@endsection
