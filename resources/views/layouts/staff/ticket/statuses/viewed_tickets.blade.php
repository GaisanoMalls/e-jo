@extends('layouts.staff.base', ['title' => 'Viewed Tickets'])

@section('page-header')
    <div class="justify-content-between d-flex flex-wrap ticket__content__top">
        <div class="col-lg-7 col-md-5">
            <h3 class="page__header__title">Tickets</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Tickets</li>
                <li class="breadcrumb-item active">Viewed</li>
            </ol>
        </div>
    </div>
@endsection

@section('main-content')
    <div class="row">
        <div class="ticket__section">
            <div class="col-12">
                <div class="card d-flex flex-column tickets__card p-0">
                    <div class="tickets__card__header pb-0 pt-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-column me-3">
                                <h6 class="card__title">Viewed Tickets</h6>
                                <p class="card__description">
                                    Respond the tickets sent by the requester
                                </p>
                            </div>
                            <div class="d-flex">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 button__header"
                                    data-bs-toggle="modal" data-bs-target="#filterUserWithRolesModal">
                                    <i class="fa-solid fa-filter"></i>
                                    <span class="button__name">Add filter</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @livewire('staff.ticket-status.viewed')
                </div>
            </div>
        </div>
    </div>
@endsection
