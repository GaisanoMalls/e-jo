@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Manage - Tag'])

@section('manage-header-title')
Tags
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Tags</li>
</ol>
@endsection

@section('manage-content')
<div class="row gap-4">
    <div class="tags__section">
        @include('layouts.staff.system_admin.manage.tags.includes.modal.add_tag_modal')
        <div class="col-12 content__container mb-4">
            <div class="card card__rounded__and__no__border">
                <div class="table__header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="card__title">Ticket Tags</h6>
                            <small class="fw-semibold">Use tags to help you find tickets</small>
                            <p class="card__description">
                                Tags help you organize and categorize the tickets in E-JO. Tags are labels attached
                                to the tickets that help you identify the contents of a message.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div
                                class="d-flex align-items-center justify-content-end justify-content-lg-end justify-content-md-end">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center ms-auto gap-2 btn btn__add__tag"
                                    data-bs-toggle="modal" data-bs-target="#addNewTagModal">
                                    <i class="fa-solid fa-plus"></i>
                                    Add new tag
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 content__container">
            <div class="card card__rounded__and__no__border pt-4">
                <div class="table-responsive custom__table">
                    @if (!$tags->isEmpty())
                    @include('layouts.staff.system_admin.manage.tags.includes.tag_list')
                    @else
                    <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                        <small style="font-size: 14px;">No departments.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@if ($errors->storeTag->any())
@push('modal-with-error')
<script>
    $(function () {
        $('#addNewTagModal').modal('show');
    });

</script>
@endpush
@endif