<div class="modal fade help__topic__modal" id="addNewHelpTopicModal" tabindex="-1"
    aria-labelledby="addNewHelpTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">Add new help topic</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.help_topic.store') }}" method="post" autocomplete="off" id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-2">
                                        <label for="name" class="form-label form__field__label">Name</label>
                                        <input type="text" name="name" class="form-control form__field" id="name"
                                            value="{{ old('name') }}" placeholder="Enter help topic name">
                                        @error('name', 'storeHelpTopic')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="sla" class="form-label form__field__label">
                                            Serice Level Agreement (SLA)
                                        </label>
                                        <select name="sla" placeholder="Select (required)" data-search="true"
                                            data-silent-initial-value-set="true">
                                            <option value="" selected disabled>Choose an SLA</option>
                                            @foreach ($slas as $sla)
                                            <option value="{{ $sla->id }}" {{ old('sla')==$sla->id ? 'selected' : '' }}>
                                                {{ $sla->time_unit }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('sla', 'storeHelpTopic')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="department" class="form-label form__field__label">
                                            Service Department
                                        </label>
                                        <select name="service_department" placeholder="Select (required)"
                                            data-search="true" data-silent-initial-value-set="true"
                                            id="helpTopicServiceDepartmentDropdown">
                                            <option value="" selected disabled>Choose a department</option>
                                            @foreach ($serviceDepartments as $serviceDepartment)
                                            <option value="{{ $serviceDepartment->id }}" {{
                                                old('service_department')==$serviceDepartment->id ? 'selected' : '' }}>
                                                {{ $serviceDepartment->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('service_department', 'storeHelpTopic')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="team" class="form-label form__field__label">
                                            Team
                                            <small id="helpTopicCountTeams"></small>
                                            <br>
                                            <small id="helpTopicNoTeamsMessage" class="text-danger fw-normal"
                                                style="font-size: 12px;"></small>
                                        </label>
                                        <select name="team" placeholder="Select (optional)" data-search="true"
                                            data-silent-initial-value-set="true" id="helpTopicTeamsDropdown">
                                        </select>
                                        @error('service_department', 'storeHelpTopic')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <small class="fw-bold my-3">Approvals</small>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label form__field__label">
                                            Level of approval
                                        </label>
                                        <select id="levelOfApproverDropdown" name="level_of_approval"
                                            placeholder="Select (required)" data-search="false"
                                            data-silent-initial-value-set="true">
                                            <option value="" selected>N/A</option>
                                            @foreach ($levelOfApprovals as $level)
                                            <option value="{{ $level->id }}" {{ old('level_of_approval')==$level->id
                                                ? 'selected' : '' }}>
                                                {{ $level->description }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('level_of_approval', 'storeHelpTopic')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <div class="row" id="selectApproverContainer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn m-0 btn__modal__footer btn__send">Save</button>
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>