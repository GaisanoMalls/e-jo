@extends('layouts.auth.base', ['title' => 'Account Creation'])

@section('form-title')
    <div class="d-flex flex-column mb-3">
        <h5 class="mb-0 login__form__user__type">Account Creation</h5>
        <small class="my-2 text-muted">Please fill in the details below to create a new account.</small>
    </div>
@endsection

@section('form-section')
    @livewire('auth.create-account')
@endsection

