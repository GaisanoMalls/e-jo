@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Help Topics'])

@section('manage-header-title')
    Help Topics
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active">Help Topics</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row gap-4">
        <div class="help__topics__section">
            @livewire('staff.help-topic.create-help-topic')
            @livewire('staff.help-topic.form.field-modal')
            <div class="col-12 content__container">
                <div class="card card__rounded__and__no__border">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                        <h6 class="mb-0 table__name shadow">List of help topics</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                data-bs-toggle="modal" data-bs-target="#addNewHelpTopicModal">
                                <i class="fa-solid fa-plus"></i>
                                <span class="button__name">Add New</span>
                            </button>
                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                data-bs-toggle="modal" data-bs-target="#customFieldModal">
                                <i class="fa-solid fa-file-circle-plus"></i>
                                <span class="button__name">Add Form</span>
                            </button>
                        </div>
                    </div>
                    @livewire('staff.help-topic.help-topic-list')
                </div>
            </div>
        </div>
    </div>
@endsection
