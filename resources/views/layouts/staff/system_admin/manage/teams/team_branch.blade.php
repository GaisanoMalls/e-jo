@extends('layouts.staff.system_admin.manage.manage_main', ['title' => 'Manage Team'])

@section('manage-header-title')
Manage Team
@endsection

@section('manage-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Manage</li>
    <li class="breadcrumb-item active">Team</li>
</ol>
@endsection

@section('manage-content')
<div class="row">
    <div class="departments__section assign__branch__department">
        <div class="col-12 content__container">
            <div class="row">
                <div class="col-xl-3 col-md-4 mb-2">
                    <div class="card card__rounded__and__no__border pt-4">
                        <div class="flex flex-column gap-2">
                            <div class="px-4">
                                <h6 style="color: #212529; font-weight: 600; font-size: 0.95rem;">Assign Branch</h6>
                            </div>
                            <div class="assign__form__container px-4 py-3">
                                <form action="{{ route('staff.manage.team.assign_branch.store') }}" method="post">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label form__field__label">
                                            <small>Team</small>
                                        </label>
                                        <select name="team" placeholder="Choose a service department" data-search="true"
                                            data-silent-initial-value-set="true" id="teamsDropdown">
                                            <option value="" selected disabled>Choose a team</option>
                                            @foreach ($teams as $team)
                                            <option value="{{ $team->id }}"
                                                {{ old('team') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('team', 'storeTeamBranch')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mb-2" id="serviceDepartmentFieldContainer">
                                        <label class="form-label form__field__label">
                                            <small>Service Department</small>
                                        </label>
                                        <input type="text" name="name" class="form-control form__field"
                                            id="serviceDepartmentField" disabled readonly
                                            style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label form__field__label">
                                            <small>Assign to branch</small>
                                            <small id="countBranches"></small>
                                            <br>
                                            <small id="noBranchMessage" class="text-danger fw-normal"
                                                style="font-size: 12px;"></small>
                                        </label>
                                        <select name="branch" placeholder="Choose a branch" data-search="true"
                                            data-silent-initial-value-set="true" id="branchDropdown">
                                            <option value="" selected disabled>Choose a branch</option>
                                            @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('branch', 'storeTeamBranch')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mt-3 mb-2 d-flex aling-items-center gap-3">
                                        <a href="{{ route('staff.manage.team.index') }}" type="button"
                                            class="btn w-100 m-0 btn__branch__department__cancel">Back</a>
                                        <button type="submit"
                                            class="btn w-100 m-0 btn__branch__department__save">Assign</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-md-8">
                    <div class="card card__rounded__and__no__border pt-4">
                        <div class="mb-2" style="padding: 0 29px;">
                            <h6 style="color: #212529; font-weight: 600; font-size: 0.95rem;">
                                Departments
                                assigned to branches
                            </h6>
                        </div>
                        <div class="table-responsive custom__table">
                            @include('layouts.staff.system_admin.manage.teams.includes.team_branch_list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
