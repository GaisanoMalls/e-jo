@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Service Level Agreements'])

@section('manage-header-title')
SLA Plans
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">SLA Plans</li>
</ol>
@endsection

@section('manage-content')
<div class="row gap-4">
    <div class="sla__section">
        @livewire('staff.sla-plan.create-sla')
        <div class="col-12 content__container mb-4">
            <div class="card card__rounded__and__no__border">
                <div class="table__header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="card__title">Service Level Agreement</h6>
                            <p class="card__description">
                                The agreed upon turnaround time in which a ticket needs to be answered or resolved.
                                It may
                                depend on priority levels such as low, medium, or high. For example, if the SLA of
                                First
                                Resolution Time is 4 hours, all tickets that were attended to within 4 hours meet
                                the SLA.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div
                                class="d-flex align-items-center justify-content-start justify-content-lg-end justify-content-md-end">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 btn btn__add__sla"
                                    data-bs-toggle="modal" data-bs-target="#addNewSLAModal">
                                    <i class="fa-solid fa-plus"></i>
                                    Create SLA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 content__container">
            <div class="card card__rounded__and__no__border pt-4">
                @livewire('staff.sla-plan.sla-list')
            </div>
        </div>
    </div>
</div>
@endsection