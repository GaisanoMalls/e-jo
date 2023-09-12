@extends('layouts.user.account.account_settings_base', [
'title' =>
'Update Password - ' .
auth()->user()->profile->getFullName(),
])

@section('account-content-header-title')
Update Password
@endsection

@section('account-content')
<form action="{{ route('user.account_settings.updatePassword') }}" method="post">
    @method('PUT')
    @csrf
    <div class="card d-flex flex-column gap-4 account__info__fields__container">
        <div class="account__form__container">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label input__field__label">Current Password</label>
                        <input type="password" name="current_password"
                            class="form-control input__field @error('current_password') is-invalid @enderror"
                            id="currentPassword" value="{{ old('current_password') }}">
                        @error('current_password', 'updatePassword')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">{{-- DO NOT DELETE. JUST LEAVE IT EMPTY --}}</div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="newPassword" class="form-label input__field__label">New Password</label>
                        <input type="password" name="new_password"
                            class="form-control input__field @error('new_password') is-invalid @enderror"
                            id="newPassword">
                        @error('new_password', 'updatePassword')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label input__field__label">Confirm Password</label>
                        <input type="password" name="confirm_password"
                            class="form-control input__field @error('confirm_password') is-invalid @enderror"
                            id="confirmPassword">
                        @error('confirm_password', 'updatePassword')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn w-auto btn__save__account">Save Profile</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection