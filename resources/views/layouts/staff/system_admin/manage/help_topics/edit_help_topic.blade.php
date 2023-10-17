@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Edit - ' . $helpTopic->name])

@section('manage-header-title')
Edit Help Topic
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item">Edit</li>
    <li class="breadcrumb-item active">Help Topic</li>
</ol>
@endsection

@section('manage-content')
<div class="row justify-content-center help__topics__section">
    <div class="col-xxl-9 col-lg-12">
        <div class="card d-flex flex-column gap-2 help__topic__details__card">
            <div class="help__topic__details__container d-flex flex-wrap mb-4 justify-content-between">
                <h6 class="card__title">Current Help Topic Setup</h6>
                <small class="text-muted" style="font-size: 12px;">
                    Last updated:
                    {{ $helpTopic->dateUpdated() }}
                </small>
            </div>
            <form action="{{ route('staff.manage.help_topic.update', $helpTopic->id) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" value="{{ $helpTopic->id }}" id="helpTopicID">
                <div class="row gap-4 help__topic__details__container">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label form__field__label">Name</label>
                                    <input type="text" name="name" class="form-control form__field" id="name"
                                        value="{{ old('name', $helpTopic->name) }}" placeholder="Enter name (required)">
                                    @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Service Level Agreements (SLA)</label>
                                    <select name="sla" data-search="false" placeholder="Select (required)">
                                        @foreach ($serviceLevelAgreements as $sla)
                                        <option value="{{ $sla->id }}" {{ $sla->id ===
                                            $helpTopic->service_level_agreement_id
                                            ? 'selected' : '' }}>
                                            {{ $sla->time_unit }}
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $helpTopic->service_department_id }}"
                                        id="helpTopicCurrentServiceDepartmentId">
                                    <label class="form-label form__field__label">Service Department</label>
                                    <select name="service_department" data-search="false"
                                        id="editHelpTopicServiceDepartmentsDropdown" placeholder="Select (required)">
                                        @foreach ($serviceDepartments as $serviceDepartment)
                                        <option value="{{ $serviceDepartment->id }}" {{ $serviceDepartment->id ===
                                            $helpTopic->service_department_id
                                            ? 'selected' : '' }}>
                                            {{ $serviceDepartment->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('service_department')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" value="{{ $helpTopic->team_id }}" id="helpTopicCurrentTeamId">
                                    <label class="form-label form__field__label">
                                        Team
                                        <span id="editHelpTopicCountTeams" style="font-size: 13px;"></span>
                                        <br>
                                        <span id="editHelpTopicNoTeamsMessage" class="text-danger fw-normal"
                                            style="font-size: 12px;"></span>
                                    </label>
                                    <select name="team" data-search="true" id="editHelpTopicTeamsDropdown"
                                        placeholder="Select (required)">
                                    </select>
                                    @error('team')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <small class="fw-bold my-3">Approvals</small>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label form__field__label">Level of Approval</label>
                                    <select name="level_of_approval" data-search="false"
                                        id="editHelpTopicLevelOfApprovalDropdown" placeholder="Select (required)">
                                        <option value="" selected>N/A</option>
                                        @foreach ($levelOfApprovals as $level)
                                        <option value="{{ $level->id }}" {{ in_array($level->id,
                                            $helpTopic->levels->pluck('id')->toArray())
                                            ? 'selected' : '' }}>
                                            {{ $level->description }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('level_of_approval')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <div class="row" id="editSelectApproverContainer">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 my-4">
                                <small class="fw-bold my-3">Current Approvers</small>
                                <div class="d-flex flex-column gap-3 mt-3 mb-4">
                                    @foreach ($helpTopic->levels as $level)
                                    <div
                                        class="position-relative bg-light p-3 mt-4 rounded-3 current__approvers__container">
                                        <h6 class="mb-0 level__label">Level {{ $level->value }}</h6>
                                        <div class="d-flex flex-wrap gap-3 mt-3">
                                            @foreach ($levelApprovers as $levelApprover)
                                            @foreach ($approvers as $approver)
                                            @if ($levelApprover->user_id == $approver->id &&
                                            $levelApprover->level_id == $level->id )
                                            <div class="card border-0 shadow-sm p-3 rounded-3">
                                                <div class="d-flex gap-2">
                                                    @if ($approver->profile->picture)
                                                    <img src="{{ Storage::url($approver->profile->picture) }}" alt=""
                                                        class="approver__image approver__picture">
                                                    @else
                                                    <div
                                                        class="approver__image approver__initial__as__picture d-flex align-items-center justify-content-center">
                                                        {{ $approver->profile->getNameInitial() }}
                                                    </div>
                                                    @endif
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 approver__name">
                                                            {{ $approver->profile->getFullName() }}
                                                        </h6>
                                                        <small class="approver__email">{{ $approver->email }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                    data-bs-dismiss="modal"
                                    onclick="window.location.href='{{ route('staff.manage.help_topic.index') }}'">Cancel</button>
                                <button type="submit" class="btn m-0 btn__details btn__send">Save</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection