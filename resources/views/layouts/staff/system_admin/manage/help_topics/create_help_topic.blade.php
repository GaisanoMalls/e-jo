@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Create Help Topic'])

@section('manage-header-title')
    Create Help Topic
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item">Edit</li>
        <li class="breadcrumb-item active">Help Topic</li>
    </ol>
@endsection

@section('manage-content')
    @livewire('staff.help-topic.create-help-topic')
@endsection
