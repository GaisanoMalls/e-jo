@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Approvers'])

@section('manage-header-title')
    Accounts
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item">Accounts</li>
        <li class="breadcrumb-item active">Approvers</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row gap-4">
        <div class="accounts__section">
            @livewire('staff.accounts.agent.create-agent')
            <div class="col-12 content__container">
                <div class="card d-flex flex-column gap-2 users__account__card card__rounded__and__no__border p-0">
                    <div class="users__account__card__header approver__list pb-0 pt-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="card__title mb-1">Agent Accounts</h6>
                                <p class="card__description">
                                    Reply to the requester's tickets and keeping track of them.
                                </p>
                            </div>
                            <div class="col-md-4">
                                <div
                                    class="d-flex align-items-center justify-content-end justify-content-lg-end justify-content-md-end">
                                    <button type="button"
                                        class="btn d-flex align-items-center justify-content-center gap-2 btn__add__user__account"
                                        data-bs-toggle="modal" data-bs-target="#addNewAgentModal">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="label">New</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 content__container">
                            @livewire('staff.accounts.agent.agent-list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($errors->storeAgent->any())
    @push('modal-with-error')
        <script>
            $(function() {
                $('#addNewAgentModal').modal('show');
            });
        </script>
    @endpush
@endif
