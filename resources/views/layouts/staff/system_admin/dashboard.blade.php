@extends('layouts.staff.base', ['title' => 'Dashboard'])

@section('page-header')
    <div class="d-flex align-items-center justify-content-between flex-wrap dashboard__content__top">
        <h3 class="page__header__title">Dashboard</h3>
        <p class="mb-0" style="font-size: 0.9rem;">Welcome back, <span
                class="fw-bold">{{ auth()->user()->profile->first_name }}</span>
        </p>
    </div>
@endsection

@section('main-content')
    <div class="row dashboard__section">
        <div class="col-xl-12 box-col-12 des-xl-100 content__container">
            @include('layouts.staff.includes.dashboard.ticket_statuses')
        </div>
        <div class="col-12">
            <div class="row">
                @include('layouts.staff.includes.dashboard.urgent_tickets')
                @include('layouts.staff.includes.dashboard.teams_tickets')
            </div>
        </div>
    </div>
@endsection
