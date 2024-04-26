<div class="card d-flex flex-column roles__permissions__card p-0">
    <div class="roles__permissions__card__header pb-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Assigned Permissions</h6>
                <p class="card__description">
                    Account roles with their associated permissions.
                </p>
            </div>
            @livewire('staff.roles-and-permissions.generate-default-role-permissions')
        </div>
    </div>
    @livewire('staff.roles-and-permissions.give-permission-list')
</div>
