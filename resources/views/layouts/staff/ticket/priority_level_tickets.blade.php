@extends('layouts.staff.base', ['title' => "{$priorityLevel->name} priority tickets"])
@section('page-header')
    <div class="justify-content-between d-flex flex-wrap ticket__content__top">
        <div class="col-lg-7 col-md-5">
            <h3 class="page__header__title">Priority Tickets</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Tickets</li>
                <li class="breadcrumb-item">Priority Level</li>
                <li class="breadcrumb-item active">{{ $priorityLevel->name }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('main-content')
    <div class="row">
        <div class="ticket__section">
            <div class="col-12">
                <div class="card d-flex flex-column tickets__card p-0">
                    @livewire('staff.priority-level-ticket', ['priorityLevel' => $priorityLevel])
                </div>
            </div>
        </div>
    </div>
@endsection
