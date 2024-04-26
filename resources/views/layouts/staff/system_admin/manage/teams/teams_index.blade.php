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
            @livewire('staff.teams.create-team')
            <div class="col-12 content__container">
                <div class="card card__rounded__and__no__border">
                    @livewire('staff.teams.team-list')
                </div>
            </div>
        </div>
    </div>
@endsection
