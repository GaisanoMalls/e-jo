@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'BU/Departments'])

@section('manage-header-title')
BU/Departments
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">BU/Departments</li>
</ol>
@endsection

@section('manage-content')
<div class="row">
    <div class="departments__section">
        @livewire('staff.b-u-departments.create-bu-department')
        <div class="row">
            <div class="col-12 content__container">
                <div class="card card__rounded__and__no__border">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                        <h6 class="mb-0 table__name shadow">List of BU/departments</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                data-bs-toggle="modal" data-bs-target="#addNewBUDepartmentModal">
                                <i class="fa-solid fa-plus"></i>
                                <span class="button__name">Add new</span>
                            </button>
                        </div>
                    </div>
                    @livewire('staff.b-u-departments.b-u-department-list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->storeBUDepartment->any() || session()->has('empty_branch') || session()->has('invalid_branch'))
@push('modal-with-error')
<script>
    $(function () {
        $('#addNewBUDepartmentModal').modal('show');
    });

</script>
@endpush
@endif

@if ($errors->editBUDepartment->any() || session()->has('empty_branch') || session()->has('invalid_branch') ||
session()->has('duplicate_name_error'))
{{-- Show edit modal based on the selected record/data --}}
<input type="hidden" id="buDeptId" value="{{ session('buDepartmentId') }}">
@push('modal-with-error')
<script>
    const buDeptId = document.getElementById('buDeptId');
    $(function () {
        $(`#editBUDepartment${buDeptId.value}`).modal('show');
    });

</script>
@endpush
@endif