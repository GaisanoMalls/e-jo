@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Manage BU/Departments'])

@section('manage-header-title')
Manage Department
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Department</li>
</ol>
@endsection

@section('manage-content')
<div class="row">
    <div class="departments__section assign__branch__department">
        <div class="col-12 content__container">
            <div class="row">
                <div class="col-xl-3 col-md-4 mb-2">
                    <div class="card card__rounded__and__no__border pt-4">
                        <div class="flex flex-column gap-2">
                            <div class="px-4">
                                <h6 style="color: #212529; font-weight: 600; font-size: 0.95rem;">Assign a branch</h6>
                            </div>
                            <div class="assign__form__container px-4 py-3">
                                <form action="{{ route('staff.manage.bu_department.assign_branch.store') }}"
                                    method="post">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label form__field__label">
                                            <small>BU/Department</small>
                                        </label>
                                        <select name="bu_department" placeholder="Choose a department"
                                            data-search="true" data-silent-initial-value-set="true">
                                            <option value="" selected disabled>Choose a department</option>
                                            @foreach ($buDepartments as $buDepartment)
                                            <option value="{{ $buDepartment->id }}"
                                                {{ old('bu_department') == $buDepartment->id ? 'selected' : '' }}>
                                                {{ $buDepartment->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('bu_department', 'storeBUDepartmentBranch')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label form__field__label">
                                            <small>Assign to branch</small>
                                        </label>
                                        <select name="branch" placeholder="Choose a branch" data-search="true"
                                            data-silent-initial-value-set="true">
                                            <option value="" selected disabled>Choose a branch</option>
                                            @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('branch', 'storeBUDepartmentBranch')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mt-3 mb-2 d-flex aling-items-center gap-3">
                                        <a href="{{ route('staff.manage.bu_department.index') }}" type="button"
                                            class="btn w-100 m-0 btn__branch__department__cancel">Back</a>
                                        <button type="submit"
                                            class="btn w-100 m-0 btn__branch__department__save">Assign</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-md-8">
                    <div class="card card__rounded__and__no__border pt-4">
                        <div class="mb-2" style="padding: 0 29px;">
                            <h6 style="color: #212529; font-weight: 600; font-size: 0.95rem;">
                                BU/department assigned to branches
                            </h6>
                        </div>
                        <div class="table-responsive custom__table">
                            @include('layouts.staff.system_admin.manage.bu_departments.includes.bu_department_branch_list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
