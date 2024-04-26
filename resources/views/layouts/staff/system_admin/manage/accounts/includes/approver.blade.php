<div class="accounts__section">
    @livewire('staff.accounts.approver.create-approver')
    <div class="col-12">
        <div class="card d-flex flex-column gap-2 users__account__card">
            <div class="users__account__card__header d-flex gap-2 align-items-center justify-content-between">
                <h5 class="users__account__card__name shadow" style="background-color: #3B4053;">Approvers
                </h5>
                <div class="d-flex align-items-center justify-content-end gap-2 mb-1">
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-2 btn__add__user__account"
                        data-bs-toggle="modal" data-bs-target="#addNewApproverModal">
                        <i class="fa-solid fa-plus"></i>
                        <span class="label">New</span>
                    </button>
                    <a href="{{ route('staff.manage.user_account.approvers') }}"
                        class="btn d-flex align-items-center justify-content-center gap-2 btn__view__user__accounts">
                        <i class="fa-solid fa-eye"></i>
                        <span class="label" style="white-space: nowrap;">View all</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-2 col-lg-2 col-md-2">
                    <p class="mb-2 user__role__description">
                        Approve the tickets sent by the requester.
                    </p>
                </div>
                <div class="col-xxl-10 col-lg-10 col-md-10 content__container">
                    @livewire('staff.accounts.approver.approver-list')
                </div>
            </div>
        </div>
    </div>
</div>
