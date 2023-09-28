<div class="modal slideIn animate service__department__modal" id="addNewTeamModal" tabindex="-1"
    aria-labelledby="addNewTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewTeamModalLabel">Add new team</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.team.store') }}" method="post" autocomplete="off" id="modalForm">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="mb-2">
                            <label for="name" class="form-label form__field__label">Name</label>
                            <input type="text" name="name" class="form-control form__field" id="name"
                                value="{{ old('name') }}" placeholder="Enter team name">
                            @error('name', 'storeTeam')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="department" class="form-label form__field__label">Service Department</label>
                            <select name="service_department" placeholder="Select (required)" data-search="true"
                                data-silent-initial-value-set="true">
                                <option value="" selected disabled>Choose a department</option>
                                @foreach ($serviceDepartments as $department)
                                <option value="{{ $department->id }}" {{ old('service_department')==$department->id ?
                                    'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('service_department', 'storeTeam')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label form__field__label">Assign to branch</label>
                            <select name="branches[]" placeholder="Select (required)" data-search="true" multiple>
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ in_array($branch->id, old('branches',
                                    [])) ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            @if (session()->has('empty_branch'))
                            <div class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ session()->get('empty_branch') }}
                            </div>
                            @endif
                            @if (session()->has('invalid_branch'))
                            <div class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ session()->get('invalid_branch') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn m-0 btn__modal__footer btn__send">Save</button>
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>