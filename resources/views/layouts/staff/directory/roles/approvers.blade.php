@extends('layouts.staff.directory.directory_main', ['title' => "Approver's Directory"])

@section('directory-header-title')
Approvers Directory
@endsection

@section('directory-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Department</li>
</ol>
@endsection

@section('directory-content')
<div class="card d-flex flex-column directory__card p-0">
    <div class="directory__card__header pb-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Find an approver</h6>
                <p class="card__description">
                    Search for approvers accross branches and departments.
                </p>
            </div>
        </div>
    </div>
    @if ($approvers->count() > 0)
    <div class="directory__type__card">
        <div class="table-responsive custom__table">
            <table class="table table-striped mb-0" id="table">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Name
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Email
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Branch
                        </th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Role
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvers as $approver)
                    <tr>
                        <td>
                            <a href="">
                                <div class="media d-flex align-items-center user__account__media">
                                    <div class="flex-shrink-0">
                                        @if ($approver->profile->picture != null)
                                        <img src="https://appsrv1-147a1.kxcdn.com/soft-ui-dashboard/img/team-2.jpg"
                                            alt="" class="image-fluid user__picture">
                                        @else
                                        @switch($approver->role->id)
                                        @case(2)
                                        <div class="user__name__initial" style="background-color: #9DA85C;">
                                            {{-- Department Admin --}}
                                            {{ $approver->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(3)
                                        <div class="user__name__initial" style="background-color: #3B4053;">
                                            {{-- Approver --}}
                                            {{ $approver->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(4)
                                        <div class="user__name__initial" style="background-color: #196837;">
                                            {{-- Agent --}}
                                            {{ $approver->profile->getNameInitial() }}
                                        </div>
                                        @break

                                        @case(5)
                                        <div class="user__name__initial" style="background-color: #24695C;">
                                            {{-- User/Requester --}}
                                            {{ $approver->profile->getNameInitial() }}
                                        </div>
                                        @break
                                        @endswitch
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3 w-100">
                                        <a href="" class="d-flex flex-column gap-1 w-100">
                                            <span class="user__name">{{ $approver->profile->getFullName() }}</span>
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->email }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->branch->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $approver->role->name }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert text-center d-flex flex-wrap align-items-center justify-content-center gap-2 py-4 mx-4"
        role="alert" style="background-color: #F5F7F9; font-size: 14px;">
        <i class="fa-solid fa-circle-info" style="fcolor: #D32839;"></i>
        Empty records for approvers in this directory.
    </div>
    @endif
</div>
@endsection
