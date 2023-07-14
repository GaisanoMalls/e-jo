@extends('layouts.staff.base', ['title' => 'Announcements'])

@section('page-header')
    <div class="d-flex justify-content-between">
        <h3 class="page__header__title">Announcement</h3>
        <button type="button" class="btn d-flex align-items-center justify-content-center gap-2 btn__add__new__annoucement"
            data-bs-toggle="modal" data-bs-target="#addNewAnnouncement">
            <i class="bi bi-plus-lg"></i>
            New
        </button>
    </div>
@endsection

@section('main-content')
    @include('layouts.staff.system_admin.announcement.includes.modal.add_announcement_modal_form')
    <div class="row mt-3 announcement__section">
        <div class="col-xxl-2 col-lg-2 col-md-3 col-12">
            @include('layouts.staff.system_admin.announcement.includes.announcement_tab')
        </div>
        <div class="col-xxl-10 col-lg-10 col-md-9 col-12">
            @include('layouts.staff.system_admin.announcement.includes.announcement_card')
        </div>
    </div>
@endsection

@if ($errors->storeAnnouncement->any())
    @push('modal-with-error')
        <script>
            $(function() {
                $('#addNewAnnouncement').modal('show');
            });
        </script>
    @endpush
@endif
