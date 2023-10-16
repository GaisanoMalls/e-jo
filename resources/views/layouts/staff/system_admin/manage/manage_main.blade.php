@extends('layouts.staff.base', ['title' => $title ?? 'Manage'])

@section('page-header')
<div class="justify-content-between d-flex flex-wrap ticket__content__top">
    <div class="w-100 d-flex flex-wrap justify-content-between">
        <h3 class="page__header__title">
            @yield('manage-header-title', 'Manage')
        </h3>
        @section('manage-breadcrumbs')
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Manage</li>
            <li class="breadcrumb-item active">Home</li>
        </ol>
        @show
    </div>
</div>
@endsection

@section('main-content')
<div class="settings__content">
    <div class="row dashboard">
        <div class="col-xl-12 settings__container">
            <div class="row mt-3">
                <div class="col-12">
                    @include('layouts.staff.system_admin.manage.manage_tab')
                    @yield('manage-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection