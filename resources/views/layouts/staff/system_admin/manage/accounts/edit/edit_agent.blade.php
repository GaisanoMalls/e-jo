@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Edit - ' . $agent->profile->getFullName()])

@section('manage-header-title')
Edit Agent
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item">Accounts</li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection

@section('manage-content')
@livewire('staff.accounts.agent-update-password', ['agent' => $agent])
<div class="row accounts__section justify-content-center">
    <div class="col-xxl-9 col-lg-12">
        <div class="card d-flex flex-column gap-2 users__account__card">
            <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                <h6 class="card__title">Agent's Information</h6>
                <small class="text-muted" style="font-size: 12px;">
                    Last updated:
                    @if ($agent->dateUpdated() > $agent->profile->dateUpdated())
                    {{ $agent->dateUpdated() }}
                    @else
                    {{ $agent->profile->dateUpdated() }}
                    @endif
                </small>
            </div>
            <form action="{{ route('staff.manage.user_account.agent.update', $agent->id) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" id="agentUserID" value="{{ $agent->id }}">
                <div class="row gap-4 user__details__container">
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label form__field__label">First
                                        name</label>
                                    <input type="text" name="first_name" class="form-control form__field"
                                        id="first_name" value="{{ old('first_name', $agent->profile->first_name) }}"
                                        placeholder="Enter first name (required)">
                                    @error('first_name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label form__field__label">Middle name</label>
                                    <input type="text" name="middle_name" class="form-control form__field"
                                        id="middle_name" value="{{ old('middle_name', $agent->profile->middle_name) }}"
                                        placeholder="Enter middle name (optional)">
                                    @error('middle_name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label form__field__label">Last name</label>
                                    <input type="text" name="last_name" class="form-control form__field" id="last_name"
                                        value="{{ old('last_name', $agent->profile->last_name) }}"
                                        placeholder="Enter last name (required)">
                                    @error('last_name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Suffix</label>
                                    <select name="suffix" data-search="false" placeholder="Select (optional)">
                                        <option value="" selected>N/A</option>
                                        @foreach ($suffixes as $suffix)
                                        <option value="{{ $suffix->name }}" {{ $suffix->name ==
                                            $agent->profile->suffix ?
                                            'selected' : '' }}>
                                            {{ $suffix->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('suffix')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Login Credentials</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label form__field__label">Email</label>
                                    <input type="email" name="email" class="form-control form__field" id="email"
                                        value="{{ old('email', $agent->email) }}" placeholder="Enter email (required)">
                                    @error('email')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="d-flex align-items-center">
                                    <button type="button"
                                        class="btn m-0 btn__details btn__update__password d-flex align-items-center gap-2 justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                        <i class="fa-solid fa-key"></i>
                                        Update Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Work Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $agent->branch_id }}" id="agentCurrentBranchId">
                                    <label class="form-label form__field__label">Branch</label>
                                    <select name="branch" data-search="true" id="editAgentBranchDropdown"
                                        placeholder="Select (required)">
                                        <option value="" selected disabled>Choose a branch</option>
                                        @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $agent->branch_id
                                            ? 'selected'
                                            : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $agent->department_id }}"
                                        id="agentCurrentBUDepartmentId">
                                    <label class="form-label form__field__label">
                                        BU/Department
                                        <span id="editAgentCountBUDepartments" style="font-size: 13px;"></span>
                                        <br>
                                        <span id="editAgentNoBUDepartmentMessage" class="text-danger fw-normal"
                                            style="font-size: 12px;"></span>
                                    </label>
                                    <select name="bu_department" data-search="true" id="editAgentBUDepartmentDropdown"
                                        placeholder="Select (required)">
                                    </select>
                                    @error('bu_department')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $agent->team_id }}" id="agentCurrentTeamId">
                                    <label class="form-label form__field__label">
                                        Team
                                        <span id="editAgentCountTeams" style="font-size: 13px;"></span>
                                        <br>
                                        <span id="editAgentNoTeamsMessage" class="text-danger fw-normal"
                                            style="font-size: 12px;"></span>
                                    </label>
                                    <select name="teams[]" data-search="true" id="editAgentTeamsDropdown"
                                        placeholder="Select (required)" multiple>
                                    </select>
                                    @if (session()->has('empty_teams'))
                                    <div class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ session()->get('empty_teams') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="branch" class="form-label form__field__label">
                                        Service Department
                                    </label>
                                    <select name="service_department" data-search="true"
                                        id="editAgentServiceDepartmentDropdown" placeholder="Select (required)">
                                        @foreach ($serviceDepartments as $serviceDepartment)
                                        <option value="{{ $serviceDepartment->id }}" {{ $serviceDepartment->id ==
                                            $agent->service_department_id
                                            ? 'selected' : '' }}>
                                            {{ $serviceDepartment->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('service_department', 'editServiceDeptAdmin')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal"
                                onclick="window.location.href='{{ route('staff.manage.user_account.agents') }}'">Cancel</button>
                            <button type="submit" class="btn m-0 btn__details btn__send">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Modal Scripts --}}
@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event => {
        $('#editPasswordModal').modal('hide');
    });
</script>
@endpush