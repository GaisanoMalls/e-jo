@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Branches'])

@section('manage-header-title')
Branches
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Branches</li>
</ol>
@endsection

@section('manage-content')
<div class="row">
    <div class="branch__section">
        @livewire('staff.branches.create-branch')
        <div class="col-12 content__container">
            <div class="card card__rounded__and__no__border">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                    <h6 class="mb-0 table__name shadow">Mall Branches</h6>
                    <button type="button"
                        class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                        data-bs-toggle="modal" data-bs-target="#addNewBranchModal">
                        <i class="fa-solid fa-plus"></i>
                        <span class="button__name">Add new</span>
                    </button>
                </div>
                @livewire('staff.branches.branch-list')
            </div>
        </div>
    </div>
</div>
@endsection