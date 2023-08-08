@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Service Departments'])

@section('manage-header-title')
Teams
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Teams</li>
</ol>
@endsection

@section('manage-content')
<div class="row">
    <div class="team__section">
        @include('layouts.staff.system_admin.manage.teams.includes.modal.team_add_form_modal')
        <div class="col-12 content__container">
            <div class="card card__rounded__and__no__border">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1 table__header">
                    <h6 class="mb-0 table__name shadow">List of teams</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <button type="button"
                            class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                            data-bs-toggle="modal" data-bs-target="#addNewTeamModal">
                            <i class="fa-solid fa-plus"></i>
                            <span class="button__name">Add new</span>
                        </button>
                    </div>
                </div>
                <div class="table-responsive custom__table">
                    @if (!$teams->isEmpty())
                    @include('layouts.staff.system_admin.manage.teams.includes.team_list')
                    @else
                    <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                        <small style="font-size: 14px;">No records for teams.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->storeTeam->any() || session()->has('empty_branch') || session()->has('invalid_branch'))
@push('modal-with-error')
<script>
    $(function () {
        $('#addNewTeamModal').modal('show');
    });

</script>
@endpush
@endif