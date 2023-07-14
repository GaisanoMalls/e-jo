@extends('layouts.staff.settings.settings_main')

@section('manage-header-title')
    Agents
@endsection

@section('settings-breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Settings</li>
        <li class="breadcrumb-item active">Agents</li>
    </ol>
@endsection

@section('manage-content')
    <div class="card border-0 p-0 card__settings mb-3">
        <div class="card__settings__content__main">
            <div class="d-flex flex-wrap justify-content-center align-items-center justify-content-between pb-3">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="d-flex gap-2 py-1">
                        <h6 class="settings__title">Agents</h6>
                        <small class="settings__records__count">6 items</small>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="d-flex align-items-center justify-content-end gap-3 py-1">
                        <div class="d-flex align-items-center position-relative">
                            <form action="" method="post">
                                <input type="text" class="settings__txtsearch__agent d-inline-block w-100"
                                    placeholder="Search...">
                            </form>
                            <i class="fa-solid fa-magnifying-glass search__icon"></i>
                        </div>
                        <button
                            class="d-flex gap-2 align-items-center justify-content-center btn btn-sm settings__add__department action__button">
                            <i class="fa-solid fa-user-plus"></i>
                        </button>
                        <button
                            class="d-flex gap-2 align-items-center justify-content-center btn btn-sm settings__add__department action__button">
                            <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xxl-3">
                    <div class="card text-center settings__agent__card">
                        <div class="d-flex flex-column">
                            <img src="https://appsrv1-147a1.kxcdn.com/soft-ui-dashboard/img/team-1.jpg" alt=""
                                class="image-fluid m-auto settings__agent__picture">
                            <p class="mb-1 settings__agent__name">Jessie Lee</p>
                            <p class="settings__agent__department">
                                College of Business Administration Education
                            </p>
                            <div class="mb-2">
                                <a href="" class="settings__agent__view__details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-3">
                    <div class="card text-center settings__agent__card">
                        <div class="d-flex flex-column">
                            <img src="https://laravel.pixelstrap.com/viho/assets/images/dashboard-2/6.png" alt=""
                                class="image-fluid m-auto settings__agent__picture">
                            <p class="mb-1 settings__agent__name">Wilson Hill</p>
                            <p class="settings__agent__department">
                                College of Computing Education
                            </p>
                            <div class="mb-2">
                                <a href="" class="settings__agent__view__details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-3">
                    <div class="card text-center settings__agent__card">
                        <div class="d-flex flex-column">
                            <img src="https://laravel.pixelstrap.com/viho/assets/images/dashboard-2/7.png" alt=""
                                class="image-fluid m-auto settings__agent__picture">
                            <p class="mb-1 settings__agent__name">Anderson Banson</p>
                            <p class="settings__agent__department">
                                College of Arts and Sciences Education
                            </p>
                            <div class="mb-2">
                                <a href="" class="settings__agent__view__details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-3">
                    <div class="card text-center settings__agent__card">
                        <div class="d-flex flex-column">
                            <img src="https://laravel.pixelstrap.com/viho/assets/images/dashboard-2/1.png" alt=""
                                class="image-fluid m-auto settings__agent__picture">
                            <p class="mb-1 settings__agent__name">Thompson Lee</p>
                            <p class="settings__agent__department">
                                College of Legal Education
                            </p>
                            <div class="mb-2">
                                <a href="" class="settings__agent__view__details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-3">
                    <div class="card text-center settings__agent__card">
                        <div class="d-flex flex-column">
                            <img src="https://laravel.pixelstrap.com/viho/assets/images/dashboard-2/8.png" alt=""
                                class="image-fluid m-auto settings__agent__picture">
                            <p class="mb-1 settings__agent__name">Williams Reed</p>
                            <p class="settings__agent__department">
                                College of Computing Education
                            </p>
                            <div class="mb-2">
                                <a href="" class="settings__agent__view__details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xxl-3">
                    <div class="card text-center settings__agent__card">
                        <div class="d-flex flex-column">
                            <img src="https://laravel.pixelstrap.com/viho/assets/images/dashboard-2/3.png" alt=""
                                class="image-fluid m-auto settings__agent__picture">
                            <p class="mb-1 settings__agent__name">Johnson Allon</p>
                            <p class="settings__agent__department">
                                College Of Business Administration Education
                            </p>
                            <div class="mb-2">
                                <a href="" class="settings__agent__view__details">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
