@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Approvers'])

@section('manage-header-title')
Accounts
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item">Accounts</li>
    <li class="breadcrumb-item active">Approvers</li>
</ol>
@endsection

@section('manage-content')
<div class="row gap-4">
    <div class="accounts__section">
        @include('layouts.staff.system_admin.manage.accounts.includes.modal.add_service_dept_admin_modal_form')
        <div class="col-12 content__container">
            <div class="card d-flex flex-column gap-2 users__account__card card__rounded__and__no__border p-0">
                <div class="users__account__card__header approver__list pb-0 pt-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="card__title mb-1">Service Dept. Admin Accounts</h6>
                            <p class="card__description">
                                Department admins can add, update and remove users, and manage department-level
                                settings.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div
                                class="d-flex align-items-center justify-content-end justify-content-lg-end justify-content-md-end">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 btn__add__user__account"
                                    data-bs-toggle="modal" data-bs-target="#addNewServiceDeptAdminModal">
                                    <i class="fa-solid fa-plus"></i>
                                    <span class="label">New</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 content__container">
                        @if (!$serviceDepartmentAdmins->isEmpty())
                        <div class="card account__type__card card__rounded__and__no__border">
                            <div class="table-responsive custom__table">
                                <table class="table table-striped mb-0" id="table">
                                    <thead>
                                        <tr>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Name
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Service Department
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Branch
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                BU/Department
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Status
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Date
                                                Added
                                            </th>
                                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                                Date Updated
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($serviceDepartmentAdmins as $serviceDepartmentAdmin)
                                        @include('layouts.staff.system_admin.manage.accounts.includes.modal.confirm_delete_deptadmin_modal')
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('staff.manage.user_account.service_department_admin.view_details', $serviceDepartmentAdmin->id) }}">
                                                    <div class="media d-flex align-items-center user__account__media">
                                                        <div class="flex-shrink-0">
                                                            @if ($serviceDepartmentAdmin->profile->picture)
                                                            <img src="{{ Storage::url($serviceDepartmentAdmin->profile->picture) }}"
                                                                alt="" class="image-fluid user__picture">
                                                            @else
                                                            <div class="user__name__initial"
                                                                style="background-color: #9DA85C;">
                                                                {{ $serviceDepartmentAdmin->profile->getNameInitial() }}
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column gap-1 ms-3 w-100">
                                                            <span class="user__name">{{
                                                                $serviceDepartmentAdmin->profile->getFullName()
                                                                }}</span>
                                                            <small>{{ $serviceDepartmentAdmin->email }}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $serviceDepartmentAdmin->serviceDepartment->name ?? '----'
                                                        }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $serviceDepartmentAdmin->branch->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $serviceDepartmentAdmin->department->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $serviceDepartmentAdmin->isActive() ? 'Active' : 'Inactive'
                                                        }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $serviceDepartmentAdmin->dateCreated() }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>
                                                        @if ($serviceDepartmentAdmin->dateUpdated() >
                                                        $serviceDepartmentAdmin->profile->dateUpdated())
                                                        {{ $serviceDepartmentAdmin->dateUpdated() }}
                                                        @else
                                                        {{ $serviceDepartmentAdmin->profile->dateUpdated() }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                                        data-tooltip-font-size="11px"
                                                        onclick="window.location.href='{{ route('staff.manage.user_account.service_department_admin.edit_details', $serviceDepartmentAdmin->id) }}'"
                                                        type="button" class="btn action__button">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button data-tooltip="Delete" data-tooltip-position="top"
                                                        data-tooltip-font-size="11px" type="button"
                                                        class="btn action__button" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteServiceDeptAdmin{{ $serviceDepartmentAdmin->id }}">
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
                        @else
                        <div class="alert text-center d-flex align-items-center justify-content-center gap-2"
                            role="alert" style="background-color: #F5F7F9; font-size: 14px;">
                            <i class="fa-solid fa-circle-info"></i>
                            Empty records for service department administrators.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->storeServiceDeptAdmin->any())
@push('modal-with-error')
<script>
    $(function () {
        $('#addNewServiceDeptAdminModal').modal('show');
    });

</script>
@endpush
@endif