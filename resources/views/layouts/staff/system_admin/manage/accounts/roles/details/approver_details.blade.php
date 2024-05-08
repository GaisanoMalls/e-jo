@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Details - ' . $approver->profile->getFullName()])

@section('manage-header-title')
    Approver Details
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item">Accounts</li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row accounts__section justify-content-center">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 users__account__card">
                <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Approver's Information</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated:
                        @if ($approver->dateUpdated() > $approver->profile->dateUpdated())
                            {{ $approver->dateUpdated() }}
                        @else
                            {{ $approver->profile->dateUpdated() }}
                        @endif
                    </small>
                </div>
                <div class="row gap-4 user__details__container">
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label form__field__label">First
                                        name</label>
                                    <input type="text" name="first_name" class="form-control form__field" id="first_name"
                                        value="{{ $approver->profile->first_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label form__field__label">Middle name</label>
                                    <input type="text" name="middle_name" class="form-control form__field"
                                        id="middle_name" value="{{ $approver->profile->middle_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label form__field__label">Last name</label>
                                    <input type="text" name="last_name" class="form-control form__field" id="last_name"
                                        value="{{ $approver->profile->last_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Suffix</label>
                                    <input type="text" name="email" class="form-control form__field" id="email"
                                        value="{{ $approver->profile->suffix }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Login Credentials</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label form__field__label">Email</label>
                                    <input type="text" name="email" class="form-control form__field" id="email"
                                        value="{{ $approver->email }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Work Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Branch</label>
                                    <input type="text" name="email" class="form-control form__field" id="email"
                                        value="{{ $approver->getBranches() }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="branch" class="form-label form__field__label">
                                        BU/Department
                                    </label>
                                    <input type="text" name="email" class="form-control form__field" id="email"
                                        value="{{ $approver->getBUDepartments() }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Assigned Permissions</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Permissions</label>
                                    <div class="rounded-3 d-flex flex-wrap align-items-center gap-2"
                                        style="border: 1px solid #e7e9eb; border-radius: 0.563rem; padding: 0.75rem 1rem;">
                                        @if ($approver->getDirectPermissions()->isNotEmpty())
                                            @foreach ($approver->getDirectPermissions() as $permission)
                                                <span class="rounded-4 d-flex align-items-center gap-1"
                                                    style="font-size: 0.74rem; background-color: #FFFFFF; padding: 0.1rem 0.6rem; border: 1px solid #dddddd; color: #212529; white-space: nowrap;">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal"
                                onclick="window.location.href='{{ route('staff.manage.user_account.approvers') }}'">Back</button>
                            <button type="button" class="btn m-0 btn__details btn__send d-flex gap-2"
                                onclick="window.location.href='{{ route('staff.manage.user_account.approver.edit_details', $approver->id) }}'">
                                <i class="bi bi-pencil"></i>
                                Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
