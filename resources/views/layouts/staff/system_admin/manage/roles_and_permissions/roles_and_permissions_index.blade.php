@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Roles & Permissions'])

@section('manage-header-title')
    Roles & Permissions
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active">Roles & Permissions</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row gap-4">
        <div class="roles__permissions__section">
            <div class="col-12">
                @include('layouts.staff.system_admin.manage.roles_and_permissions.includes.role_type_card')
            </div>
            <div class="row">
                <div class="col-md-5 mb-4">
                    @include('layouts.staff.system_admin.manage.roles_and_permissions.includes.permissions')
                </div>
                <div class="col-md-7">
                    @include('layouts.staff.system_admin.manage.roles_and_permissions.includes.give-permission')
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @if ($errors->storeUser->any())
@push('modal-with-error')
<script>
    $(function() {
                $('#addNewUserModal').modal('show');
            });
</script>
@endpush
@endif --}}
