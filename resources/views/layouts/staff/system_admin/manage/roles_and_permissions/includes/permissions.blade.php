<div class="card d-flex flex-column roles__permissions__card p-0">
    <div class="roles__permissions__card__header pb-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Permissions</h6>
                <p class="card__description">
                    Find all the users with their associated roles.
                </p>
            </div>
        </div>
    </div>
    @livewire('staff.roles-and-permissions.create-permission')
    @livewire('staff.roles-and-permissions.permission-list')
</div>
