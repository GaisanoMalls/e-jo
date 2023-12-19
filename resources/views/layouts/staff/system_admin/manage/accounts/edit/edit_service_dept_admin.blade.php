@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Edit - ' . $serviceDeptAdmin->profile->getFullName()])

@section('manage-header-title')
    Edit Service Dept. Admin
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item">Accounts</li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('manage-content')
    @livewire('staff.accounts.service-department-admin.update-service-department-admin', [
        'serviceDeptAdmin' => $serviceDeptAdmin,
    ])
@endsection
