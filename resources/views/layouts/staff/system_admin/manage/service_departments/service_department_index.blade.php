@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Service Departments'])

@section('manage-header-title')
Service Departments
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Service Departments</li>
</ol>
@endsection

@section('manage-content')
<div class="row">
    <div class="departments__section">
        @include('layouts.staff.system_admin.manage.service_departments.includes.modal.add_service_department_modal_form')
        <div class="row">
            <div class="col-12 content__container">
                <div class="card card__rounded__and__no__border">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                        <h6 class="mb-0 table__name shadow">List of service departments</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                data-bs-toggle="modal" data-bs-target="#addNewServiceDepartmentModal">
                                <i class="fa-solid fa-plus"></i>
                                <span class="button__name">Add new</span>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive custom__table">
                        @if (!$serviceDepartments->isEmpty())
                        @include('layouts.staff.system_admin.manage.service_departments.includes.service_department_list')
                        @else
                        <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                            <small style="font-size: 14px;">No departments.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->storeServiceDepartment->any())
@push('modal-with-error')
<script>
    $(function () {
        $('#addNewServiceDepartmentModal').modal('show');
    });

</script>
@endpush
@endif

@if ($errors->editServiceDepartment->any())
{{-- Show edit modal based on the selected record/data --}}
<input type="hidden" id="serviceDeptId" value="{{ session('serviceDepartmentId') }}">
@push('modal-with-error')
<script>
    const serviceDeptId = document.getElementById('serviceDeptId');
    $(function () {
        $(`#editServiceDepartment${serviceDeptId.value}`).modal('show');
    });

</script>
@endpush
@endif