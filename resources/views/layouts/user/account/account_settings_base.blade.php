@extends('layouts.user.base', [
'title' =>
$title ??
'User Profile - ' .
auth()->user()->profile->getFullName(),
])

@section('main-content')
<div class="row account__settings">
    <div class="col-xl-4 col-md-4 mb-3">
        <div class="card custom__card">
            <div class="d-flex flex-column justify-content-center align-items-center py-3">
                @if (auth()->user()->profile->picture)
                <img src="{{ Storage::url(auth()->user()->profile->picture) }}" alt="" class="user__picture">
                @else
                <div class="user__picture d-flex align-items-center p-2 justify-content-center text-white"
                    style="background-color: #1A2E35; border: 3px solid #385A64; font-size: 13px;">
                    {{ auth()->user()->profile->getNameInitial() }}</div>
                @endif
                <div class="d-flex flex-column mt-3 text-center">
                    <h6 class="mb-0 user__full__name">{{ auth()->user()->profile->getFullName() }}</h6>
                    <small>{{ auth()->user()->email }}</small>
                    <p class="mb-0 mt-3">{{ auth()->user()->department->name ?? '' }}</p>
                    <p class="mb-0 mt-4 user__date__joined">
                        Date Joined:
                        <span class="date">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
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
                @include('layouts.user.account.profile')
                @show
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.includes.toaster-message')