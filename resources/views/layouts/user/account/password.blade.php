@extends('layouts.user.account.account_settings_base', [
    'title' =>
        'Update Password - ' .
        auth()->user()->profile->getFullName(),
])

@section('account-content-header-title')
    Update Password
@endsection

@section('account-content')
    @livewire('requester.account.password')
@endsection
