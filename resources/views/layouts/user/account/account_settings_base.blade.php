@extends('layouts.user.base', [
    'title' => $title ?? 'User Profile - ' . auth()->user()->profile->getFullName,
])

@section('main-content')
    <div class="row account__settings">
        @livewire('requester.account.preview')
        <div class="col-xl-8 col-md-8">
            <div class="card custom__card p-0">
                <div class="account__content__header">
                    <h5 class="mb-0 account__content__header__title">
                    @section('account-content-header-title')
                        Edit Profile
                    @show
                </h5>
                <ul class="list-unstyled d-flex gap-4 border-bottom overflow-scroll account__content__tab">
                    <li>
                        <a href="{{ route('user.account_settings.profile') }}"
                            class="btn align-items-center border-0 account__tab__button {{ Route::is('user.account_settings.profile') ? 'active' : '' }}">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.account_settings.password') }}"
                            class="btn align-items-center border-0 account__tab__button {{ Route::is('user.account_settings.password') ? 'active' : '' }}">
                            Password
                        </a>
                    </li>
                </ul>
            </div>
            <div class="account__content">
                @section('account-content')
                    @livewire('requester.account.profile')
                @show
            </div>
        </div>
    </div>
</div>
@endsection
