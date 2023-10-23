<div class="accounts__section">
    @livewire('staff.accounts.service-department-admin.create-service-dept-admin')
    <div class="col-12">
        <div class="card d-flex flex-column gap-2 users__account__card">
            <div class="users__account__card__header d-flex align-items-center justify-content-between">
                <h5 class="users__account__card__name shadow" style="background-color: #9DA85C;">Service Dept. Admins
                </h5>
                <div class="d-flex align-items-center justify-content-end gap-2 mb-1">
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-2 btn__add__user__account"
                        data-bs-toggle="modal" data-bs-target="#addNewServiceDeptAdminModal">
                        <i class="fa-solid fa-plus"></i>
                        <span class="label">New</span>
                    </button>
                    <a href="{{ route('staff.manage.user_account.service_department_admins') }}"
                        class="btn d-flex align-items-center justify-content-center gap-2 btn__view__user__accounts">
                        <i class="fa-solid fa-eye"></i>
                        <span class="label">View all</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-2 col-lg-2 col-md-2">
                    <p class="mb-2 user__role__description">
                        Department admins can add, update and remove users, and manage department-level settings.
                    </p>
                </div>
                <div class="col-xxl-10 col-lg-10 col-md-10">
                    @livewire('staff.accounts.service-department-admin.service-dept-admin-list')
                </div>
            </div>
        </div>
    </div>
</div>