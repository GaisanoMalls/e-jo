@extends('layouts.staff.base', ['title' => $title ?? 'Directories'])

@section('page-header')
    <div class="justify-content-between d-flex flex-wrap ticket__content__top">
        <div class="w-100 d-flex flex-wrap justify-content-between">
            <h3 class="page__header__title">
                @yield('directory-header-title')
            </h3>
        @section('directory-breadcrumbs')
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Directory</li>
                <li class="breadcrumb-item active">Home</li>
            </ol>
        @show
    </div>
</div>
@endsection

@section('main-content')
<div class="directory__content">
    <div class="row dashboard">
        <div class="col-xl-12 directory__container">
            <div class="row mt-3">
                <div class="col-12">
                    @include('layouts.staff.directory.includes.directory_tab')
                    @yield('directory-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
