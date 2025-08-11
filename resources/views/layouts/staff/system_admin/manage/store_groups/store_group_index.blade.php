@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Store Groups'])

@section('manage-header-title')
    Store Groups
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active">Store Groups</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row">
        <div class="departments__section">
            @livewire('staff.store-groups.create-store-group')
            <div class="row">
                <div class="col-12 content__container">
                    <div class="card card__rounded__and__no__border">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                            <h6 class="mb-0 table__name shadow">List of Store Groups</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                    data-bs-toggle="modal" data-bs-target="#addNewStoreGroupModal">
                                    <i class="fa-solid fa-plus"></i>
                                    <span class="button__name">Add new</span>
                                </button>
                            </div>
                        </div>
                        @livewire('staff.store-groups.store-group-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
