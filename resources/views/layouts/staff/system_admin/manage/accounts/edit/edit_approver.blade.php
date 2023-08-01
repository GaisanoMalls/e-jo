@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Edit - ' . $approver->profile->getFullName()])

@section('manage-header-title')
Edit Approver
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item">Accounts</li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection

@section('manage-content')
@include('layouts.staff.system_admin.manage.accounts.edit.modal.edit_password_modal')
<div class="row accounts__section justify-content-center">
    <div class="col-xxl-9 col-lg-12">
        <div class="card d-flex flex-column gap-2 users__account__card">
            <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                <h6 class="card__title">Approver's Information</h6>
                <small class="text-muted" style="font-size: 13px;">Last updated: {{ $approver->dateUpdated() }}</small>
            </div>
            <form action="{{ route('staff.manage.user_account.approver.update', $approver->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row gap-4 user__details__container">
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label form__field__label">First
                                        name</label>
                                    <input type="text" name="first_name" class="form-control form__field"
                                        id="first_name" value="{{ $approver->profile->first_name }}">
                                    @error('first_name', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label form__field__label">Middle name</label>
                                    <input type="text" name="middle_name" class="form-control form__field"
                                        id="middle_name" value="{{ $approver->profile->middle_name }}">
                                    @error('middle_name', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label form__field__label">Last name</label>
                                    <input type="text" name="last_name" class="form-control form__field" id="last_name"
                                        value="{{ $approver->profile->last_name }}">
                                    @error('last_name', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Suffix</label>
                                    <select name="suffix" data-search="false" data-silent-initial-value-set="true">
                                        <option value="" selected>N/A</option>
                                        @foreach ($suffixes as $suffix)
                                        <option value="{{ $suffix->name }}" {{ $suffix->name ==
                                            $approver->profile->suffix ?
                                            'selected' : '' }}>
                                            {{ $suffix->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('suffix', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
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
                                        value="{{ $approver->email }}">
                                    @error('email', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="d-flex align-items-center">
                                    <button type="button"
                                        class="btn m-0 btn__details btn__update__password d-flex align-items-center gap-2 justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                        <i class="fa-solid fa-key"></i>
                                        Update Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Work Details</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $approver->branch_id }}" id="currentBranchId">
                                    <label class="form-label form__field__label">Branch</label>
                                    <select name="branch" data-search="true" data-silent-initial-value-set="true"
                                        id="editApproverBranchDropdown">
                                        <option value="" selected disabled>Choose a branch</option>
                                        @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $approver->branch_id ?
                                            'selected' : ''
                                            }}>
                                            {{ $branch->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('branch', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $approver->department_id }}"
                                        id="currentDepartmentId">
                                    <label for="branch" class="form-label form__field__label">
                                        BU/Department
                                        <span id="approverCountBUDepartments" style="font-size: 13px;"></span>
                                        <br>
                                        <span id="approverNoBUDepartmentMessage" class="text-danger fw-normal"
                                            style="font-size: 12px;"></span>
                                    </label>
                                    <select name="bu_department" data-search="true" data-silent-initial-value-set="true"
                                        id="editApproverBUDepartmentDropdown">
                                    </select>
                                    @error('bu_department', 'editApprover')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal"
                                onclick="window.location.href='{{ route('staff.manage.user_account.index') }}'">Cancel</button>
                            <button type="submit" class="btn m-0 btn__details btn__send">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if ($errors->updatePassword->any())
@push('modal-with-error')
<script>
    $(function () {
        $('#editPasswordModal').modal('show');
    });

</script>
@endpush
@endif