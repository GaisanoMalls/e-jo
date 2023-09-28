<div class="modal slideIn animate account__modal" id="addNewApproverModal" tabindex="-1"
    aria-labelledby="addNewApproverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewApproverModalLabel">Add new approver</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.user_account.approver.store') }}" method="post" autocomplete="off"
                id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="d-flex flex-column">
                                <img src="{{ asset('images/approver_creation.jpg') }}" alt=""
                                    style="height: auto; width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <h5 class="mb-4">Fill in the information</h5>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="first_name" class="form-label form__field__label">First
                                                name</label>
                                            <input type="text" name="first_name" class="form-control form__field"
                                                id="first_name" value="{{ old('first_name') }}"
                                                placeholder="Enter first name (required)">
                                            @error('first_name', 'storeApprover')
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
                                            </label>
                                            <input type="text" name="middle_name" class="form-control form__field"
                                                id="middle_name" value="{{ old('middle_name') }}"
                                                placeholder="Enter middle name (optional)">
                                            @error('middle_name', 'storeApprover')
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
                                                id="last_name" value="{{ old('last_name') }}"
                                                placeholder="Enter last name (required)">
                                            @error('last_name', 'storeApprover')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label for="suffix" class="form-label form__field__label">
                                                Suffix
                                            </label>
                                            <select name="suffix" data-search="false" placeholder="Select (optional)">
                                                <option value="" selected disabled>Choose a suffix</option>
                                                @foreach ($suffixes as $suffix)
                                                <option value="{{ $suffix->name }}" {{ old('suffix')==$suffix->name ?
                                                    'selected' : '' }}>
                                                    {{ $suffix->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('suffix', 'storeApprover')
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
                                            <label for="email" class="form-label form__field__label">Email
                                                address</label>
                                            <input type="email" name="email" class="form-control form__field" id="email"
                                                value="{{ old('email') }}" placeholder="Enter email (required)">
                                            @error('email', 'storeApprover')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="role" class="form-label form__field__label">User
                                                role</label>
                                            <input type="text" value="Approver" class="form-control form__field"
                                                disabled readonly
                                                style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="branch" class="form-label form__field__label">Branch</label>
                                            <select name="branches[]" data-search="true" placeholder="Select (required)"
                                                id="approverBranchDropdown" multiple>
                                                <option value="" selected disabled>Choose a branch</option>
                                                @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old('branch')==$branch->id ?
                                                    'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if (session()->has('empty_branches'))
                                            <div class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ session()->get('empty_branches') }}
                                            </div>
                                            @endif
                                            @if (session()->has('invalid_branches'))
                                            <div class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ session()->get('invalid_branches') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="branch" class="form-label form__field__label">
                                                BU/Department
                                                <span id="approverCountBUDepartments" style="font-size: 13px;"></span>
                                                <br>
                                                <span id="approverNoBUDepartmentMessage" class="text-danger fw-normal"
                                                    style="font-size: 12px;"></span>
                                            </label>
                                            <select name="bu_departments[]" data-search="true"
                                                placeholder="Select (required)" id="approverBUDepartmentDropdown"
                                                multiple>
                                                <option value="" selected disabled>Choose a branch</option>
                                                @foreach ($buDepartments as $department)
                                                <option value="{{ $department->id }}" {{
                                                    old('department')==$department->id ?
                                                    'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if (session()->has('empty_bu_departments'))
                                            <div class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ session()->get('empty_bu_departments') }}
                                            </div>
                                            @endif
                                            @if (session()->has('invalid_bu_departments'))
                                            <div class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ session()->get('invalid_bu_departments') }}
                                            </div>
                                            @endif
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