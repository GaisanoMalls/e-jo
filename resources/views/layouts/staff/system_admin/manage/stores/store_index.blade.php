@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Stores'])

@section('manage-header-title')
    Stores
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active">Stores</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row">
        <div class="departments__section">
            @livewire('staff.stores.create-store')
            <div class="row">
                <div class="col-12 content__container">
                    <div class="card card__rounded__and__no__border">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                            <h6 class="mb-0 table__name shadow">List of Stores</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                    data-bs-toggle="modal" data-bs-target="#addNewStoreModal">
                                    <i class="fa-solid fa-plus"></i>
                                    <span class="button__name">Add new</span>
                                </button>
                            </div>
                        </div>
                        @livewire('staff.stores.store-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
