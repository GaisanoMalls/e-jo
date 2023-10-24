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
@livewire('staff.accounts.approver.update-approver', ['approver' => $approver])
@endsection