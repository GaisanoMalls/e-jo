@extends('layouts.staff.base', ['title' => 'Tickets to assign manually'])

@section('page-header')
    <div class="justify-content-between d-flex flex-wrap ticket__content__top">
        <div class="col-lg-7 col-md-5">
            <h3 class="page__header__title">Tickets</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Tickets</li>
                <li class="breadcrumb-item active">Recommendations</li>
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
                                <h6 class="card__title">Ticket Recommendations</h6>
                            </div>
                        </div>
                    </div>
                    @livewire('staff.ticket.recommendations')
                </div>
            </div>
        </div>
    </div>
@endsection
