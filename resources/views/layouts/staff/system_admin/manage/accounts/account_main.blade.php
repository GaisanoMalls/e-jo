@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Accounts'])

@section('manage-header-title')
    Accounts
@endsection

@section('manage-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active">Accounts</li>
    </ol>
@endsection

@section('manage-content')
    <div class="row gap-4">
        @include('layouts.staff.system_admin.manage.accounts.includes.approver')
        @include('layouts.staff.system_admin.manage.accounts.includes.department_admin')
        @include('layouts.staff.system_admin.manage.accounts.includes.agent')
        @include('layouts.staff.system_admin.manage.accounts.includes.user')
    </div>
@endsection

@if ($errors->storeDeptAdmin->any())
    @push('modal-with-error')
        <script>
            $(function() {
                $('#addNewDeptAdminModal').modal('show');
            });
        </script>
    @endpush
@endif

@if ($errors->storeAgent->any())
    @push('modal-with-error')
        <script>
            $(function() {
                $('#addNewAgentModal').modal('show');
            });
        </script>
    @endpush
@endif

@if ($errors->storeApprover->any())
    @push('modal-with-error')
        <script>
            $(function() {
                $('#addNewApproverModal').modal('show');
            });
        </script>
    @endpush
@endif

@if ($errors->storeUser->any())
    @push('modal-with-error')
        <script>
            $(function() {
                $('#addNewUserModal').modal('show');
            });
        </script>
    @endpush
@endif
