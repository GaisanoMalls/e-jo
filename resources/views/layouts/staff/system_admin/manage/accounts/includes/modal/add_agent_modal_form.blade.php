<div class="modal account__modal" id="addNewAgentModal" tabindex="-1" aria-labelledby="addNewAgentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewAgentModalLabel">Add new agent</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.user_account.agent.store') }}" method="post" autocomplete="off"
                id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="d-flex flex-column">
                                <img src="{{ asset('images/agent_creation.jpg') }}" alt=""
                                    style="height: auto; width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="first_name" class="form-label form__field__label">First
                                                name</label>
                                            <input type="text" name="first_name" class="form-control form__field"
                                                id="first_name" value="{{ old('first_name') }}">
                                            @error('first_name', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="middle_name" class="form-label form__field__label">
                                                Middle name
                                                <span><small>(Optional)</small></span>
                                            </label>
                                            <input type="text" name="middle_name" class="form-control form__field"
                                                id="middle_name" value="{{ old('middle_name') }}">
                                            @error('middle_name', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="last_name" class="form-label form__field__label">Last
                                                name</label>
                                            <input type="text" name="last_name" class="form-control form__field"
                                                id="last_name" value="{{ old('last_name') }}">
                                            @error('last_name', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="suffix" class="form-label form__field__label">
                                                Suffix
                                                <span><small>(Optional)</small></span>
                                            </label>
                                            <select name="suffix" data-search="true"
                                                data-silent-initial-value-set="true">
                                                <option value="" selected disabled>Choose a suffix</option>
                                                @foreach ($suffixes as $suffix)
                                                <option value="{{ $suffix->name }}"
                                                    {{ old('suffix') == $suffix->name ? 'selected' : '' }}>
                                                    {{ $suffix->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('suffix', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="email" class="form-label form__field__label">Email
                                                address</label>
                                            <input type="email" name="email" class="form-control form__field" id="email"
                                                value="{{ old('email') }}">
                                            @error('email', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="role" class="form-label form__field__label">User
                                                role</label>
                                            <input type="text" value="Agent" class="form-control form__field" disabled
                                                readonly
                                                style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="branch" class="form-label form__field__label">
                                                Branch
                                            </label>
                                            <select name="branch" data-search="true"
                                                data-silent-initial-value-set="true" id="agentBranchesDropdown">
                                                <option value="" selected disabled>Choose a branch</option>
                                                @foreach ($global_branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ old('branch') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('branch', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="bu_department" class="form-label form__field__label">
                                                BU/Department
                                                <span id="agentCountBUDepartment" style="font-size: 13px;"></span>
                                                <br>
                                                <span id="agentNoBUDepartmentsMessage" class="text-danger fw-normal"
                                                    style="font-size: 12px;"></span>
                                            </label>
                                            <select name="bu_department" data-search="true"
                                                data-silent-initial-value-set="true" id="agentBUDepartmentsDropdown">
                                            </select>
                                            @error('bu_department', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label class="form-label form__field__label">
                                                Team
                                                <span id="agentCountTeams" style="font-size: 13px;"></span>
                                                <br>
                                                <span id="agentNoTeamMessage" class="text-danger fw-normal"
                                                    style="font-size: 12px;"></span>
                                            </label>
                                            <select name="team" data-search="true" data-silent-initial-value-set="true"
                                                id="agentTeamsDropdown">
                                            </select>
                                            @error('team', 'storeAgent')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="department" class="form-label form__field__label">
                                                Service Department
                                            </label>
                                            <select name="service_department" data-search="true"
                                                data-silent-initial-value-set="true">
                                                <option value="" selected disabled>Choose a servic department</option>
                                                @foreach ($global_service_departments as $service_department)
                                                <option value="{{ $service_department->id }}"
                                                    {{ old('service_department') == $service_department->id ? 'selected' : '' }}>
                                                    {{ $service_department->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('service_department', 'storeServiceDeptAdmin')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 px-2 mt-3">
                                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="submit"
                                                class="btn m-0 btn__modal__footer btn__send">Save</button>
                                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                                                id="btnCloseModal" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
