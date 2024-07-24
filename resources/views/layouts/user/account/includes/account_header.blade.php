<div class="card custom__card account__header">
    <div class="d-flex align-items-center gap-5 account__header__content">
        <div class="d-flex align-items-center gap-4">
            <img src="{{ asset('images/user/user.jpg') }}" alt="" class="user__picture">
            <div class="d-flex flex-column gap-1">
                <h6 class="mb-0 user__full__name">
                    {{ auth()->user()->profile->getFullName }}
                    <span class="user__status">{{ auth()->user()->isActive() ? 'Active' : 'Inactive' }}</span>
                </h6>
                <p class="mb-0 user__department d-flex gap-2">
                    <i class="bi bi-buildings-fill"></i>
                    {{ auth()->user()->getBUDepartments() }}
                </p>
            </div>
        </div>
        <div class="d-flex flex-column gap-2">
            <p class="mb-0 lbl__user__role">
                Role:
                <span class="user__role">{{ auth()->user()->role }}</span>
            </p>
            <p class="mb-0 lbl__user__role">
                Last Login:
                <span class="user__role">{{ auth()->user()->role }}</span>
            </p>
        </div>
    </div>
</div>
