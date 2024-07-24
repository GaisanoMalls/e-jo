@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Edit - ' . $user->profile->getFullName])

@section('manage-header-title')
    Edit Requester
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item">Accounts</li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('manage-content')
    @livewire('staff.accounts.requester.update-requester', ['user' => $user])
@endsection
