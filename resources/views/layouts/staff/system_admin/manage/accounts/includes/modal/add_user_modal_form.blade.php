<div class="modal account__modal" id="addNewUserModal" tabindex="-1" aria-labelledby="addNewUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewUserModalLabel">Add new requester</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.user_account.user.store') }}" method="post" autocomplete="off"
                id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="d-flex flex-column">
                                <img src="{{ asset('images/user_creation.jpg') }}" alt=""
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
                                            @error('first_name', 'storeUser')
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
                                            @error('middle_name', 'storeUser')
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
                                            @error('last_name', 'storeUser')
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
                                                <option value="{{ $suffix->name }}" {{ old('suffix')==$suffix->name ?
                                                    'selected' : '' }}>
                                                    {{ $suffix->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('suffix', 'storeUser')
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
                                                value="{{ old('email') }}">
                                            @error('email', 'storeUser')
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
                                            <input type="text" value="User / Requester" class="form-control form__field"
                                                disabled readonly
                                                style="padding: 0.75rem 1rem; font-size: 0.875rem; border-radius: 0.563rem; border: 1px solid #e7e9eb;">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="branch" class="form-label form__field__label">
                                                Branch
                                            </label>
                                            <select name="branch" data-search="true"
                                                data-silent-initial-value-set="true" id="userBranchesDropdown">
                                                <option value="" selected disabled>Choose a branch</option>
                                                @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old('branch')==$branch->id ?
                                                    'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('branch', 'storeUser')
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
                                                BU/Department
                                                <span id="userNoBUDepartmentsMessage" class="text-danger fw-semibold"
                                                    style="font-size: 13px;"></span>
                                                <span id="userCountBUDepartments" style="font-size: 13px;"></span>
                                            </label>
                                            <select name="department" data-search="true"
                                                data-silent-initial-value-set="true" id="userDepartmentsDropdown">
                                            </select>
                                            @error('department', 'storeUser')
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