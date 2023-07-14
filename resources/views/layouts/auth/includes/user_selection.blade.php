<div class="d-flex flex-column gap-3 justify-content-center user__selection gap-4 mb-4">
    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
        <img src="{{ asset('images/gmall.png') }}" class="company__logo" alt="">
        <h6 class="label__welcome__back text-center">Welcome Back!</h6>
        <p class="mb-0 text-center label__type__of__user">Choose what type of account you have.</p>
    </div>
    <div class="user__icons__container d-flex flex-column gap-4 mt-4">
        <div class="d-flex align-items-center position-relative ms-4">
            <img src="{{ asset('images/auth/user.png') }}" class="ml-3 user__icon" alt="">
            <a href="{{ route('auth.requester.login') }}"
                class="btn px-5 py-2 bg-info btn__select__user btn__user">Requester</a>
        </div>
        <div class="d-flex align-items-center justify-content-end position-relative me-4">
            <img src="{{ asset('images/auth/staff.png') }}" class="ml-3 user__icon" alt="">
            <a href="{{ route('auth.staff.login') }}"
                class="btn px-5 py-2 bg-info btn__select__user btn__staff">Staff</a>
        </div>
    </div>
</div>
