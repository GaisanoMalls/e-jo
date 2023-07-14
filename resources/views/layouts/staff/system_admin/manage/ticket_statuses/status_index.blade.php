@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Ticket Statuses'])

@section('manage-header-title')
Ticket Statuses
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Ticket Statuses</li>
</ol>
@endsection

@section('manage-content')
<div class="row gap-4">
    <div class="ticket__statuses__section">
        @include('layouts.staff.system_admin.manage.ticket_statuses.includes.modal.add_status_modal')
        <div class="col-12 content__container mb-4">
            <div class="card card__rounded__and__no__border">
                <div class="table__header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="card__title">Ticket Status</h6>
                            <p class="card__description">
                                Refers to the current state or stage of a support or service request. It provides
                                information on the progress and status of the ticket throughout its lifecycle. Ticket
                                statuses are typically defined and customized based on the needs of the organization or
                                the specific ticketing system in use.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div
                                class="d-flex align-items-center justify-content-start justify-content-lg-end justify-content-md-end">
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 btn btn__add__status"
                                    data-bs-toggle="modal" data-bs-target="#addNewTicketStatusModal">
                                    <i class="fa-solid fa-plus"></i>
                                    Create Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 content__container">
            <div class="card card__rounded__and__no__border pt-4">
                <div class="table-responsive custom__table" id="table">
                    @if ($statuses->count() > 0)
                    @include('layouts.staff.system_admin.manage.ticket_statuses.includes.status_list')
                    @else
                    <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                        <small style="font-size: 14px;">No ticket statuses.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->storeTicketStatus->any())
@push('modal-with-error')
<script>
    $(function () {
        $('#addNewTicketStatusModal').modal('show');
    });

</script>
@endpush
@endif
