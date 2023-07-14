@extends('layouts.user.account.account_settings_base', [
    'title' =>
        'Update Password - ' .
        auth()->user()->profile->getFullName(),
])

@section('account-content-header-title')
    Update Password
@endsection

@section('account-content')
    <div class="alert alert-success" role="alert" id="successMessage" style="display: none;"></div>
    <form action="{{ route('user.account_settings.updatePassword') }}" method="post" id="updatePasswordForm" autofocus="false">
        @method('PUT')
        @csrf
        <div class="card d-flex flex-column gap-4 account__info__fields__container">
            <div class="account__form__container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label input__field__label">Current Password</label>
                            <input type="password" name="current_password" class="form-control input__field"
                                id="currentPassword">
                            <span class="text-danger custom__field__message" id="current_passwordError"></span>
                        </div>
                    </div>
                    <div class="col-md-6">{{-- DO NOT DELETE. JUST LEAVE IT EMPTY --}}</div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label input__field__label">New Password</label>
                            <input type="password" name="new_password" class="form-control input__field" id="newPassword">
                            <span class="text-danger custom__field__message" id="new_passwordError"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label input__field__label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control input__field"
                                id="confirmPassword">
                            <span class="text-danger custom__field__message" id="confirm_passwordError"></span>
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
{{-- 
@section('action-js')
    <script src="{{ asset('js/pages/user/update-password.js') }}"></script>
@endsection --}}
