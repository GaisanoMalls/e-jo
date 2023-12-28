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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 content__container">
                <div class="card card__rounded__and__no__border pt-4">
                    <div class="table-responsive custom__table">
                        @if ($statuses->isNotEmpty())
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Name</th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Color</th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Tickets</th>
                                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($statuses as $status)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $status->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start gap-2 td__content">
                                                    <div class="rounded-1"
                                                        style="background-color: {{ $status->color }}; height: 16px; width: 16px; box-shadow: 0 0.25rem
                                            0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20,
                                            0.07);">
                                                    </div>
                                                    <span>{{ $status->color }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start gap-2 td__content">
                                                    <span>{{ $status->tickets()->count() }}</span>
                                                    <i class="fa-solid fa-envelope"
                                                        style="font-size: 13px; color: #8d94a1;"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-start td__content">
                                                    <span>{{ $status->dateCreated() }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                                <small style="font-size: 14px;">No records for statuses.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
