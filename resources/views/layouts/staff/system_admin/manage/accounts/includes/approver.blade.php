<div class="accounts__section">
    @include('layouts.staff.system_admin.manage.accounts.includes.modal.add_approver_modal_form')
    <div class="col-12">
        <div class="card d-flex flex-column gap-2 users__account__card">
            <div class="users__account__card__header d-flex align-items-center justify-content-between">
                <h5 class="users__account__card__name shadow" style="background-color: #3B4053;">Approvers
                </h5>
                <div class="d-flex align-items-center justify-content-end gap-2 mb-1">
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-2 btn__add__user__account"
                        data-bs-toggle="modal" data-bs-target="#addNewApproverModal">
                        <i class="fa-solid fa-plus"></i>
                        <span class="label">New</span>
                    </button>
                    <a href=""
                        class="btn d-flex align-items-center justify-content-center gap-2 btn__view__user__accounts">
                        <i class="fa-solid fa-eye"></i>
                        <span class="label">View all</span>
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
                    @if ($approvers->count() > 0)
                    <div class="card account__type__card">
                        <div class="table-responsive custom__table">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;;">Name
                                        </th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Branch
                                        </th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                            BU/Department
                                        </th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Status
                                        </th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date
                                            Added
                                        </th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Last
                                            Active
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvers as $approver)
                                    @include('layouts.staff.system_admin.manage.accounts.includes.modal.confirm_delete_approver_modal')
                                    <tr>
                                        <td>
                                            <a href="">
                                                <div class="media d-flex align-items-center user__account__media">
                                                    <div class="flex-shrink-0">
                                                        @if ($approver->profile->picture)
                                                        <img src="{{ Storage::url($approver->profile->picture) }}"
                                                            alt="" class="image-fluid user__picture">
                                                        @else
                                                        <div class="user__name__initial"
                                                            style="background-color: #3B4053;">
                                                            {{ $approver->profile->getNameInitial() }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 w-100">
                                                        <a href="" class="d-flex flex-column gap-1 w-100">
                                                            <span class="user__name">{{
                                                                $approver->profile->getFullName() }}</span>
                                                            <small>{{ $approver->email }}</small>
                                                        </a>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start td__content">
                                                <span>{{ $approver->branch->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start td__content">
                                                <span>{{ $approver->department->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start td__content">
                                                <span>{{ $approver->isActive() ? 'Active' : 'Inactive' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start td__content">
                                                <span>{{ $approver->dateCreated() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center text-start td__content">
                                                <span>{{ $approver->dateUpdated() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                                <button
                                                    onclick="window.location.href='{{ route('staff.manage.user_account.approver.details', $approver->id) }}'"
                                                    type="button" class="btn action__button">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" href="" class="btn action__button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteApprover{{ $approver->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="d-flex align-items-center justify-content-center mt-2">
                        <a href="" class="text-center m-auto" style="font-size: 13px;">View all</a>
                    </div> --}}
                    @else
                    <div class="alert text-center d-flex align-items-center justify-content-center gap-2" role="alert"
                        style="background-color: #F5F7F9; font-size: 14px;">
                        <i class="fa-solid fa-circle-info"></i>
                        Empty records for approvers.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>