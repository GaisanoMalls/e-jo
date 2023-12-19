@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Edit - ' . $helpTopic->name])

@section('manage-header-title')
    Edit Help Topic
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item">Edit</li>
        <li class="breadcrumb-item active">Help Topic</li>
    </ol>
@endsection

@section('manage-content')
    @livewire('staff.help-topic.update-help-topic', ['helpTopic' => $helpTopic])
@endsection
